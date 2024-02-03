<?php

namespace App\Http\Controllers\Manager;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Arrivage;
use App\Models\Bande;
use App\Models\Client;
use App\Models\LivraisonInfo;
use App\Models\LivraisonPayment;
use App\Models\LivraisonProduct;
use App\Models\Magasin;
use App\Models\Produit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LivraisonController extends Controller
{

    public function create()
    {
        $pageTitle = 'Enregistrer une Livraison';
        $staff = auth()->user();
        $magasins = Magasin::active()->orderBy('name')->get(); 
        $clients = Client::active()->orderBy('name')->get();
        $staffs = User::active()->where('user_type','staff')->with('magasin')->orderBy('lastname')->get();
        $produits = Produit::joinRelationship('arrivage')->where('quantity_restante','>',0)->groupby('bande_id')->with('arrivage.bande')->get();
         
        return view('manager.livraison.create', compact('pageTitle', 'magasins', 'produits','clients','staffs'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'magasin' => 'required|exists:magasins,id',
            'staff' => 'required|exists:users,id', 
            'sender_name' => 'required|max:255',
            'sender_email' => 'required|email|max:255',
            'sender_phone' => 'required|string|max:255', 
            'receiver_name' => 'required|max:255', 
            'receiver_phone' => 'required|string|max:255',
            'receiver_address' => 'required|max:255',
            'quantite' => 'required|array',
            'unite' => 'required|array',
            'categorie' => 'required|array',
            'price' => 'required|array',
            'produit' => 'required|array',
            'estimate_date'    => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'payment_status'   => 'required|integer|in:0,1',
        ]);

        $sender                      = auth()->user();
        $livraison                     = new LivraisonInfo();
        $livraison->invoice_id         = getTrx();
        $livraison->code               = getTrx();
        $livraison->sender_magasin_id   = $sender->magasin_id;
        $livraison->sender_staff_id    = $sender->id; 
        if($sender->user_type !='Staff'){
            $livraison->sender_staff_id    = $request->staff;
        }

        $livraison->sender_name        = $request->sender_name;
        $livraison->sender_email       = $request->sender_email;
        $livraison->sender_phone       = $request->sender_phone;
        $livraison->sender_address     = $request->sender_address;

        $livraison->receiver_staff_id    = $request->staff;
        $livraison->receiver_client_id    = $request->client;
        $livraison->receiver_magasin_id = $request->magasin;
        if($request->client=='Autre'){
            $client = new Client();
            $client->name      = $request->receiver_name;
            $client->email     = $request->receiver_email;
            $client->phone     = $request->receiver_phone;
            $client->address   = $request->receiver_address;
            $client->save();
            $livraison->receiver_client_id = $client->id; 
        }else{
            $client = Client::find($request->client);
            $client->name      = $request->receiver_name;
            $client->email     = $request->receiver_email;
            $client->phone     = $request->receiver_phone;
            $client->address   = $request->receiver_address;
            $client->save();
            $livraison->receiver_client_id    = $client->id;
        }
        $livraison->receiver_name      = $request->receiver_name;
        $livraison->receiver_email     = $request->receiver_email;
        $livraison->receiver_phone     = $request->receiver_phone;
        $livraison->receiver_address   = $request->receiver_address;
 
        $livraison->estimate_date      = $request->estimate_date;
        $livraison->save();

        $subTotal = 0;
        $quantite = $request->quantite;
        $unite = $request->unite;
        $categorie = $request->categorie;
        $prix = $request->price;
        $produit = $request->produit;

        $data = [];
        foreach($request->quantite as $arrivageId => $categorieId) {
            $categorieArray = array_filter($categorieId);
            
            foreach($categorieArray as $categorieId => $total) {
               
            $qte = $total; 
             
            if($qte==null){
                continue;
            }

            $price = $prix[$arrivageId][$categorieId] * $qte;
            $catId = $categorie[$arrivageId][$categorieId];
            $uniteId = $unite[$arrivageId][$categorieId];
            $produitId= $produit[$arrivageId][$categorieId];
            $subTotal += $price;

            $livraisonProduit = new LivraisonProduct();
            $livraisonProduit->livraison_info_id = $livraison->id;
            $livraisonProduit->livraison_produit_id = $produitId;
            $livraisonProduit->qty = $qte;
            $livraisonProduit->fee = $price;
            $livraisonProduit->type_price = $prix[$arrivageId][$categorieId];
            $livraisonProduit->save();
            
            if($livraisonProduit->id !=null){
                $product = Produit::where('id',$produitId)->first();
                if($product !=null)
                { 
                    $product->quantity_use = $product->quantity_use + $qte;
                    $product->quantity_restante = $product->quantity_restante - $qte;
                    $product->save();
                }

                // $arrivage = Arrivage::where('id',$arrivageId)->first();
                // if($arrivage !=null)
                // { 
                //     $arrivage->total_restant = $product->total_restant - $qte;
                //     $arrivage->quantity_restante = $product->quantity_restante - $qte;
                //     $arrivage->save();
                // }
                
            }
        }
 
        }
 

        $discount                        = $request->discount ?? 0;
        // $discountAmount                  = ($subTotal / 100) * $discount;
        $discountAmount                  =  $discount;
        $totalAmount                     = $subTotal - $discountAmount;

        $livraisonPayment                  = new LivraisonPayment();
        $livraisonPayment->livraison_info_id = $livraison->id;
        $livraisonPayment->amount          = $subTotal;
        $livraisonPayment->discount        = $discountAmount;
        $livraisonPayment->final_amount    = $totalAmount+$request->frais_livraison;
        $livraisonPayment->frais_livraison = $request->frais_livraison;
        $livraisonPayment->percentage      = $discount;
        $livraisonPayment->status          = $request->payment_status;
        $livraisonPayment->save();

        if ($livraisonPayment->status == Status::PAID) {
            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $sender->id;
            $adminNotification->title     = 'Paiement Livraison ' . $sender->username;
            $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
            $adminNotification->save();
        }

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $sender->id;
        $adminNotification->title     = 'Nouvelle livraison créée par ' . $sender->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
        $adminNotification->save();

        $notify[] = ['success', 'La livraison a été ajoutée avec succès'];
        return to_route('manager.livraison.invoice', encrypt($livraison->id))->withNotify($notify);
    }

    public function getProduit()
    { 
        $totalrecu = 0;  
        
        if(request()->bande !=null){

        $arrivages = Produit::joinRelationship('arrivage')->whereIn('bande_id', request()->bande)->where('quantity_restante','>',0)->get();  

        if (count($arrivages)) {

                        $somme = 0;

                        $results = '';

                        $i = 0;
                        $k = 1;
                        $totalrecu = 0;
                         
                        foreach($arrivages as $data) { 

                            $s = 1;  
                                if($data->quantity_restante==0){
                                    continue;
                                } 
                                $totalrecu +=$data->quantity_restante;
                                $results .= '<tr class="single-item"><td><input type="hidden" name="produit[' . $data->arrivage_id . '][' . $data->categorie_id . ']" value="' . $data->id . '"><input type="hidden" name="unite[' . $data->arrivage_id . '][' . $data->categorie_id . ']" value="' . $data->categorie->unite_id . '">' . $data->categorie->unite->name . '</td>';
                                $results .= '<td><input type="hidden" name="categorie[' . $data->arrivage_id . '][' . $data->categorie_id . ']" value="' . $data->categorie_id . '"/>' . $data->categorie->name . '</td>';
                                $results .= '<td><button class="btn btn-info" type="button">'. $data->arrivage->bande->numero_bande . '</button></td>';
                                $results .= '<td><input type="hidden" name="price[' . $data->arrivage_id . '][' . $data->categorie_id . ']" value="' . $data->price . '" class="price" />' . $data->price . '</td>';
                                $results .= '<td><input type="hidden" name="quantiteinitiale['. $data->arrivage_id .']['. $data->categorie_id .']"  value="' . $data->quantity_restante. '" /><button class="btn btn-danger" type="button">' . $data->quantity_restante . '</button></td>';
                                $results .= '<td><div class="input-group"><input type="number" name="quantite[' . $data->arrivage_id . '][' . $data->categorie_id . ']" min="0" max="' . $data->quantity_restante . '" value="0" id="qte-' . $k . '"  class="form-control totaux quantity-' . $i . ' st-' . $s . '" onchange=getQuantite(' . $i . ',' . $k . ',' . $s . ') ></div></td>'; 
                                $k++;
                                $s++;
                                $i++;
                                $results .= '</tr>';
                           
                        }
 
        } else {
            $results = '<span style="
                text-align: center;
                color: #f70000;
                font-weight: bold;
            ">Aucun arrivage de produits n\'est disponible!</span>';
        }
    }else {
            $results = '<span style="
                text-align: center;
                color: #f70000;
                font-weight: bold;
            ">Veuillez choisir un numero de bande.</span>';
        }

        $contents['tableau'] = $results;
        $contents['total'] = $totalrecu;

        return $contents;
    }

    public function update(Request $request, $id)
    {

        $id = decrypt($id);

        $request->validate([
            'magasin'           => 'required|exists:magasins,id',
            'staff'           => 'required|exists:users,id', 
            'sender_name'      => 'required|max:255',
            'sender_email'     => 'required|email|max:255',
            'sender_phone'     => 'required|string|max:255', 
            'receiver_name'    => 'required|max:255', 
            'receiver_phone'   => 'required|string|max:255',
            'receiver_address' => 'required|max:255',
            'items'            => 'required|array',
            'items.*.produit'     => 'required|integer|exists:produits,id',
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.amount'   => 'required|numeric|gt:0',
            'items.*.name'     => 'nullable|string',
            'estimate_date'    => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'payment_status'   => 'required|integer|in:0,1',
        ]);

        $sender                      = auth()->user();
        $livraison                     = LivraisonInfo::findOrFail($id);
        $livraison->invoice_id         = getTrx();
        $livraison->code               = getTrx();
        $livraison->sender_magasin_id   = $sender->magasin_id; 
        $livraison->sender_staff_id    = $sender->id; 
        if($sender->user_type !='Staff'){
            $livraison->sender_staff_id    = $request->staff;
        }

        $livraison->sender_name        = $request->sender_name;
        $livraison->sender_email       = $request->sender_email;
        $livraison->sender_phone       = $request->sender_phone;
        $livraison->sender_address     = $request->sender_address;

        $livraison->receiver_staff_id    = $request->staff;
        $livraison->receiver_client_id    = $request->client;
        $livraison->receiver_magasin_id = $request->magasin;
        $livraison->receiver_name      = $request->receiver_name;
        $livraison->receiver_email     = $request->receiver_email;
        $livraison->receiver_phone     = $request->receiver_phone;
        $livraison->receiver_address   = $request->receiver_address;
        $livraison->status   = Status::COURIER_DELIVERYQUEUE;
        $livraison->estimate_date      = $request->estimate_date;
        $livraison->save();

        LivraisonProduct::where('livraison_info_id', $id)->delete();

        $subTotal = 0;
        $data = [];
        foreach ($request->items as $item) {
            $livraisonProduit = Produit::where('id', $item['produit'])->first();
            if (!$livraisonProduit) {
                continue;
            }
            $price     = $livraisonProduit->price * $item['quantity'];
            $subTotal += $price;

            $data[] = [
                'livraison_info_id' => $livraison->id,
                'livraison_produit_id' => $livraisonProduit->id,
                'qty'             => $item['quantity'], 
                'fee'             => $price,
                'type_price'      => $livraisonProduit->price,
                'created_at'      => now(),
            ];
            $livraisonProduit->quantity = $livraisonProduit->quantity - $item['quantity'];
            $livraisonProduit->quantity_use = $livraisonProduit->quantity_use + $item['quantity'];
            $livraisonProduit->save();
        }
        LivraisonProduct::insert($data);

        $discount = $request->discount ?? 0;
        // $discountAmount = ($subTotal / 100) * $discount;
        $discountAmount = $discount;
        $totalAmount = $subTotal - $discountAmount;

        $user = auth()->user();
        if ($request->payment_status == Status::PAID || $request->payment_status == Status::UNPAID) {

            $livraisonPayment               = LivraisonPayment::where('livraison_info_id', $livraison->id)->first();
            $livraisonPayment->amount       = $subTotal;
            $livraisonPayment->discount     = $discountAmount;
            $livraisonPayment->final_amount = $totalAmount+$request->frais_livraison;
            $livraisonPayment->frais_livraison = $request->frais_livraison;
            $livraisonPayment->percentage   = $request->discount;
            $livraisonPayment->status       = $request->payment_status;
            $livraisonPayment->save();

            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $sender->id;
            $adminNotification->title     = $livraison->code . ' Paiement Livraison Updated  by ' . $user->username;
            $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
            $adminNotification->save();
        }

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $sender->id;
        $adminNotification->title     = $livraison->code . ' La livraison a été mise à jour par ' . $user->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
        $adminNotification->save();

        $notify[] = ['success', 'La livraison a été mise à jour avec succès'];
        return to_route('manager.livraison.invoice', encrypt($livraison->id))->withNotify($notify);
    }
    public function edit($id)
    {
        
        $pageTitle   = 'Modifier de Livraison';
        $id = decrypt($id);
        $user = auth()->user();
        $magasins = Magasin::active()->orderBy('name')->get();
        $produits = Produit::active()->with('categorie')->orderBy('name')->get();
        $clients = Client::active()->orderBy('name')->get();
        $staffs = User::active()->where('user_type','staff')->with('magasin')->orderBy('lastname')->get();
        
        $livraisonInfo = LivraisonInfo::with('products.produit', 'payment')->where('sender_magasin_id', $user->magasin_id)->where('id', $id)->firstOrFail();
       
        // if ($livraisonInfo->status != Status::COURIER_DELIVERED) {
        //     $notify[] = ['error', "Vous ne pouvez pas modifier une commande déjà livrée."];
        //     return back()->with($notify);
        // }
        return view('manager.livraison.edit', compact('pageTitle', 'livraisonInfo', 'produits', 'magasins','clients','staffs'));
    }

    public function livraisonInfo()
    {
        $pageTitle    = "Liste des Commandes";
        $user = auth()->user();
        $livraisonInfos = $this->livraisons();
        $staffs = User::active()->where([['user_type','staff'],['magasin_id',$user->magasin_id]])->orderBy('lastname')->get();
         
        return view('manager.livraison.index', compact('pageTitle', 'livraisonInfos','staffs'));
    }
 
    public function invoice($id)
    {
        $pageTitle = 'Facture';
        $livraisonInfo = LivraisonInfo::with('products.produit.categorie', 'payment')->findOrFail(decrypt($id));
        return view('manager.livraison.invoice', compact('pageTitle', 'livraisonInfo'));
    }

 
    public function sentQueue()
    {
        $pageTitle    = 'En attente';
        $user         = auth()->user();
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code', 'receiverMagasin:name'])->where('sender_magasin_id', $user->magasin_id)->where('status', Status::COURIER_QUEUE)->orderBy('id', 'DESC')
            ->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('manager.livraison.sentQueue', compact('pageTitle', 'livraisonLists'));
    }

    public function livraisonDispatch()
    {
        $pageTitle    = 'Livraison Expédiée';
        $user         = auth()->user();
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code', 'receiverMagasin:name'])->where('sender_magasin_id', $user->magasin_id)->where('status', Status::COURIER_DISPATCH)->orderBy('id', 'DESC')
            ->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('manager.livraison.dispatch', compact('pageTitle', 'livraisonLists'));
    }

    public function upcoming()
    {
        $pageTitle    = 'Livraison Encours';
        $user         = auth()->user();
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code'])->where('receiver_magasin_id', $user->magasin_id)->where('status', Status::COURIER_UPCOMING)->orderBy('id', 'DESC')
            ->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('manager.livraison.upcoming', compact('pageTitle', 'livraisonLists'));
    }

    public function dispatched($id)
    {
        $user                = auth()->user();
        $livraisonInfo         = LivraisonInfo::where('sender_magasin_id', $user->magasin_id)->findOrFail($id);
        $livraisonInfo->status = Status::COURIER_DELIVERYQUEUE;
        $livraisonInfo->save();
        $notify[] = ['success', 'La Livraison a été expédiée avec succès'];
        return back()->withNotify($notify);
    }

    public function deliveryLivraison($id)
    {
        $user                = auth()->user();
        $livraisonInfo         = LivraisonInfo::where('receiver_magasin_id', $user->magasin_id)->findOrFail($id);
        $livraisonInfo->status = Status::COURIER_DELIVERED;
        $livraisonInfo->save();

        $notify[] = ['success', 'La Livraison a été livrée avec succès'];
        return back()->withNotify($notify);
    }

    public function deliveryQueue()
    {
        $pageTitle    = 'En attente de Reception';
        $user = auth()->user();
        $livraisonLists = $this->livraisons('deliveryQueue');
        $sommeTotal = $livraisonLists->pluck('paymentList')->collapse()->sum('final_amount');

        $staffs = User::active()->where([['user_type','staff'],['magasin_id',$user->magasin_id]])->orderBy('lastname')->get();
        return view('manager.livraison.deliveryQueue', compact('pageTitle', 'livraisonLists','staffs','sommeTotal'));
    }

    public function delivered()
    {

        $pageTitle    = 'Livraison livrée';
        $user = auth()->user();
        $livraisonLists = $this->livraisons('delivered');

         
        $sommeTotal = $livraisonLists->pluck('paymentList')->collapse()->sum('final_amount');
        
        $staffs = User::active()->where([['user_type','staff'],['magasin_id',$user->magasin_id]])->orderBy('lastname')->get();
        return view('manager.livraison.list', compact('pageTitle', 'livraisonLists','staffs','sommeTotal'));
    }

    public function credit()
    {
        $pageTitle    = 'Livraison à Crédit'; 
        $user = auth()->user();
        $livraisonLists = LivraisonPayment::dateFilter()->joinRelationship('info')
        ->where([['livraison_infos.status', Status::COURIER_DELIVERED]])
        ->where('livraison_payments.status', '!=',1)
        ->when(request()->staff, function ($query, $staff) {
            $query->where('receiver_staff_id',$staff); 
        })
        ->when(request()->magasin, function ($query, $magasin) {
             $query->where('livraison_infos.status',$magasin); 
        })
        ->when(request()->payment_status, function ($query, $payment_status) {
            $query->where('livraison_infos.status',$payment_status); 
       });
        $sommeTotal = $livraisonLists->sum('final_amount');  
        $sommePartiel = $livraisonLists->sum('partial_amount');
        $sommeTotal = $sommeTotal - $sommePartiel;
        $livraisonLists = $livraisonLists->orderby('id','desc')->paginate(getPaginate());
        $staffs = User::active()->where([['user_type','staff'],['magasin_id',$user->magasin_id]])->orderBy('lastname')->get();

        return view('manager.livraison.credit', compact('pageTitle', 'livraisonLists','sommeTotal','staffs'));
    }

    public function annule()
    {
        $pageTitle    = 'Livraison Annulée'; 
        $user = auth()->user();
        $livraisonLists = LivraisonPayment::dateFilter()->joinRelationship('info')
        ->where([['livraison_infos.status', Status::COURIER_CANCEL]])
        ->when(request()->staff, function ($query, $staff) {
            $query->where('receiver_staff_id',$staff); 
        })
        ->when(request()->magasin, function ($query, $magasin) {
             $query->where('livraison_infos.status',$magasin); 
        })
        ->when(request()->payment_status, function ($query, $payment_status) {
            $query->where('livraison_infos.status',$payment_status); 
       });
        $sommeTotal = $livraisonLists->sum('final_amount');  
         
        $livraisonLists = $livraisonLists->paginate(getPaginate());
        $staffs = User::active()->where([['user_type','staff'],['magasin_id',$user->magasin_id]])->orderBy('lastname')->get();

        return view('manager.livraison.annule', compact('pageTitle', 'livraisonLists','sommeTotal','staffs'));
    }

    protected function livraisons($scope = null)
    {
        $user = auth()->user();
        $livraisons = LivraisonInfo::where(function ($query) use ($user) {
            $query->Where('receiver_magasin_id', $user->magasin_id);
        });
        if ($scope) {
            $livraisons = $livraisons->$scope();
        }
        
        $livraisons = $livraisons->dateFilter()->searchable(['code'])->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo','paymentList')
        ->when(request()->staff, function ($query, $staff) {
            $query->where('receiver_staff_id',$staff); 
        })
        ->when(request()->status, function ($query, $status) {
             $query->where('status',$status); 
        })
        ->where(function ($q) {
            $q->OrWhereHas('payment', function ($myQuery) {
                if(request()->payment_status != null){
                    $myQuery->where('status',request()->payment_status);
                }
            });
        })
        ->orderBy('livraison_infos.id','DESC')
        ->paginate(getPaginate());
        return $livraisons;
    }

    public function receive($id)
    {
        $livraisonInfo         = LivraisonInfo::findOrFail($id);
        $livraisonInfo->status = Status::COURIER_DELIVERYQUEUE;
        $livraisonInfo->save();
        $notify[] = ['success', 'Livraison received successfully'];
        return back()->withNotify($notify);
    }

    public function livraisonList()
    {
        $user         = auth()->user();
        $pageTitle    = 'Liste des Livraisons';
     
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code', 'receiverMagasin:name'])->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo','paymentList') 
            ->when(request()->staff, function ($query, $staff) {
                $query->where('receiver_staff_id',$staff); 
            })
            ->when(request()->status, function ($query, $status) {
                 $query->where('status',$status); 
            })
            ->where(function ($q) {
                $q->OrWhereHas('payment', function ($myQuery) {
                    if(request()->payment_status != null){
                        $myQuery->where('status',request()->payment_status);
                    }
                });
            })
           ->where('receiver_magasin_id', $user->magasin_id);

        $sommeTotal = $livraisonLists->get(); 
        $sommeTotal = $sommeTotal->pluck('paymentList')->collapse()->sum('final_amount');

        $livraisonLists = $livraisonLists->orderBy('id', 'DESC')->paginate(getPaginate());

        $staffs = User::active()->where([['user_type','staff'],['magasin_id',$user->magasin_id]])->orderBy('lastname')->get();
        return view('manager.livraison.list', compact('pageTitle', 'livraisonLists','staffs','sommeTotal'));
    }

    public function details($id)
    {
        $pageTitle   = 'Details de la Livraison';
        $livraisonInfo = LivraisonInfo::with('products.produit.categorie')->findOrFail(decrypt($id));
        return view('manager.livraison.details', compact('pageTitle', 'livraisonInfo'));
    }

    public function payment(Request $request)
    {
        
        
        $request->validate([
            'code' => 'required'
        ]);
        
        $user = auth()->user();
        
        // $livraison = LivraisonInfo::where('code', $request->code)
        //     ->where(function ($query) use ($user) {
        //         $query->where('sender_magasin_id', $user->magasin_id)->orWhere('receiver_magasin_id', $user->magasin_id);
        //     })
        //     ->whereIn('status', [Status::COURIER_QUEUE, Status::COURIER_DELIVERYQUEUE])
        //     ->firstOrFail();
        
        $livraison = LivraisonInfo::where('code', $request->code)->first();
        
        $livraisonPayment = LivraisonPayment::where('livraison_info_id', $livraison->id)->first();

        $livraisonPayment->receiver_id = $user->id;
        $livraisonPayment->magasin_id   = $user->magasin_id;
        $livraisonPayment->date        = Carbon::now();
        $partial = $livraisonPayment->partial_amount+$request->montant;
        $final = $livraisonPayment->final_amount;
        if($partial>$final){
            $partial = $final;
        }
        $montant = $final - $partial;
        if($montant>0){
            $livraisonPayment->status      = Status::PARTIAL;
        }else{
            $livraisonPayment->status      = Status::PAID;
        }
        $livraisonPayment->partial_amount = $partial;
        $livraisonPayment->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'Paiement Livraison ' . $user->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
        $adminNotification->save();

        $notify[] = ['success', 'Paiement terminé'];
        $code = $request->code;
        if($livraison->status==3){
            return back()->withNotify($notify);
        }else{
            return back()->with(compact('code'))->withNotify($notify);
        }
    }

    public function deliveryStore(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:livraison_infos,code',
        ]);
        $user = auth()->user();
        $livraison = LivraisonInfo::where('code', $request->code)->where('status', Status::COURIER_DELIVERYQUEUE)->firstOrFail();

        $livraison->receiver_staff_id = $user->id;
        $livraison->status            = Status::COURIER_DELIVERED;
        $livraison->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'Livraison reçue ' . $user->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
        $adminNotification->save();

        $notify[] = ['success', 'Reception terminée'];
        $code = $request->code;
        $payment = LivraisonPayment::where('livraison_info_id', $livraison->id)->first();
        if($payment->status==1){
            return back()->withNotify($notify);
        }else{
            return back()->with(compact('code'))->withNotify($notify);
        }
    }

    public function livraisonAllDispatch(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $ids  = $request->id;
        $id   = explode(',', $ids);
        $user = auth()->user();
        LivraisonInfo::whereIn('id', $id)->where('sender_magasin_id', $user->magasin_id)->update(['status' => Status::COURIER_DISPATCH]);
    }

    public function cash()
    {
        $user = auth()->user();
        $pageTitle = 'Revenus des Livraisons';
        $magasinIncomeLists = LivraisonPayment::where('receiver_id', $user->id)->select(DB::raw('*,SUM(final_amount) as totalAmount'))->groupBy('date')->paginate(getPaginate());
        return view('manager.livraison.cash', compact('pageTitle', 'magasinIncomeLists'));
    }

    public function sentLivraisonList()
    {
        $user = auth()->user();
        $pageTitle = 'Total Livraison Expédiée';
        $livraisonInfo = LivraisonInfo::dateFilter()->searchable(['code']);
        $livraisonLists = $livraisonInfo->where('sender_magasin_id', $user->magasin_id)->whereIn('status', [Status::COURIER_DISPATCH, Status::COURIER_DELIVERYQUEUE, Status::COURIER_DELIVERED])->orderBy('id', 'DESC')
            ->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        
        return view('manager.livraison.list', compact('pageTitle', 'livraisonLists'));
    }

    public function receivedLivraisonList()
    {
        $user = auth()->user();
        $pageTitle = 'Liste des Livraisons reçues';
        $livraisonLists = LivraisonInfo::where('receiver_staff_id', $user->id)->orderBy('id', 'DESC')->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')
            ->paginate(getPaginate());
        return view('manager.livraison.list', compact('pageTitle', 'livraisonLists'));
    }

    public function destroy($id) 
    {
        
        LivraisonInfo::find(decrypt($id))->delete();
        LivraisonPayment::where('livraison_info_id',decrypt($id))->delete();
        LivraisonProduct::where('livraison_info_id',decrypt($id))->delete();
        
        $notify[] = ['success', 'La commande a été supprimé avec succès.'];
        return back()->withNotify($notify);
    }

}
