<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Client;
use App\Models\Magasin;
use App\Models\Payment;
use App\Models\Produit;
use App\Constants\Status;
use App\Models\LivraisonInfo;
use App\Exports\ExportCommandes;
use App\Models\LivraisonPayment;
use App\Models\LivraisonProduct;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LivraisonController extends Controller
{

    public function livraisonInfo()
    {
        $pageTitle    = "Informations de Livraisons";
        $staffs = User::active()->get();
        $magasins = Magasin::get(); 

        $livraisonInfos = LivraisonInfo::dateFilter()->searchable(['code'])->filter(['status','receiver_magasin_id','sender_magasin_id'])->where(function ($q) {
            $q->OrWhereHas('payment', function ($myQuery) {
                if(request()->payment_status != null){
                    $myQuery->where('status',request()->payment_status);
                }
            });
        })
        ->when(request()->staff, function ($query, $staff) {
            $query->where('receiver_staff_id',$staff); 
        })
        ->when(request()->magasin, function ($query, $magasin) {
            $query->where('receiver_magasin_id',$magasin);
        });

        $sommeTotal = $livraisonInfos->with('paymentList')->get(); 
        $sommeTotal = $sommeTotal->pluck('paymentList')->collapse()->sum('final_amount');
         
        $livraisonInfos= $livraisonInfos->orderBy('id', 'DESC')->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('admin.livraison.index', compact('pageTitle', 'livraisonInfos','staffs','magasins','sommeTotal'));
    }

    public function brouillon(){

        $pageTitle    = "Informations de Livraisons en Brouillon";
        $staffs = User::active()->get();
        $magasins = Magasin::get(); 
        
        $livraisonInfos = LivraisonInfo::dateFilter()->searchable(['code'])->filter(['status','receiver_magasin_id','sender_magasin_id'])->where(function ($q) { 
            $q->WhereHas('product', function ($myQuery) {
                 $myQuery->where('etat',0); 
            });
        }) ->when(request()->staff, function ($query, $staff) {
            $query->where('receiver_staff_id',$staff); 
        })
        ->when(request()->magasin, function ($query, $magasin) {
            $query->where('receiver_magasin_id',$magasin);
        });
        $sommeTotal = 0;  
         
        $livraisonInfos= $livraisonInfos->orderBy('id', 'DESC')->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
         
        return view('admin.livraison.brouillon', compact('pageTitle', 'livraisonInfos','staffs','magasins','sommeTotal'));
    }
    public function livraisonDetail($id)
    {
        $livraisonInfo = LivraisonInfo::with('products.produit.categorie', 'payment')->findOrFail($id);
        $pageTitle   = "Details de Livraison: " . $livraisonInfo->code;
        return view('admin.livraison.details', compact('pageTitle', 'livraisonInfo'));
    }
    public function livraisonBrouillonDetail($id)
    {
        $livraisonInfo = LivraisonInfo::with('products.produit.categorie', 'payment')->findOrFail(decrypt($id));
        $pageTitle   = "Details de Livraison: " . $livraisonInfo->code;
        return view('admin.livraison.details-brouillon', compact('pageTitle', 'livraisonInfo'));
    }

    public function editBrouillon($id)
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
        return view('admin.livraison.edit-brouillon', compact('pageTitle', 'livraisonInfo', 'produits', 'magasins','clients','staffs'));
    }

    public function updateBrouillon(Request $request, $id)
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

        //LivraisonProduct::where('livraison_info_id', $id)->delete();

        $subTotal = 0;
        $data = [];
        foreach ($request->items as $item) {
            $livraisonProduit = Produit::where('id', $item['produit'])->first();
            if (!$livraisonProduit) {
                continue;
            }
            $price     = $livraisonProduit->price * $item['quantity'];
            $subTotal += $price;

            // $data[] = [
            //     'livraison_info_id' => $livraison->id,
            //     'livraison_produit_id' => $livraisonProduit->id,
            //     'qty'             => $item['quantity'], 
            //     'fee'             => $price,
            //     'type_price'      => $livraisonProduit->price,
            //     'created_at'      => now(),
            // ];
            // $livraisonProduit->quantity = $livraisonProduit->quantity - $item['quantity'];
            // $livraisonProduit->quantity_use = $livraisonProduit->quantity_use + $item['quantity'];
            // $livraisonProduit->save();
        }
        // LivraisonProduct::insert($data);

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
        return to_route('admin.livraison.invoice', encrypt($livraison->id))->withNotify($notify);
    }
    
    public function invoice($id)
    {
        $livraisonInfo = LivraisonInfo::with('products.produit.categorie', 'payment')->findOrFail($id);
        $pageTitle   = "Facture";
        return view('admin.livraison.invoice', compact('pageTitle', 'livraisonInfo'));
    }

    public function exportExcel()
    { 
        $filename = 'commandes-' . gmdate('dmYhms') . '.xlsx';
        return Excel::download(new ExportCommandes, $filename);
    }
    public function delete($id)
    { 
        LivraisonProduct::where([['livraison_info_id', decrypt($id)],['etat',0]])->delete();
        $notify[] = ['success', 'La commande en brouillon a été supprimé avec succès'];
        return back()->withNotify($notify);
    }
}
