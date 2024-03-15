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
           
           
            $q->WhereHas('product', function ($myQuery) {
                if(request()->etat != null){
                    $etat=1;
                    if(request()->etat==2){
                        $etat = 0;
                    }
                $myQuery->where('etat',$etat); 
                } 
           });
           
            
            $q->WhereHas('payment', function ($myQuery) {
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
        
        $livraisonInfos = LivraisonInfo::dateFilter()->searchable(['code'])->filter(['status','receiver_magasin_id','sender_magasin_id'])->where([['estimate_date','>=',gmdate('Y-m-d')],['livraison_infos.status',2]])->where(function ($q) { 
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
        
        $livraisonInfo = LivraisonInfo::with('products.produit', 'payment')->where('id', $id)->firstOrFail();
       
        // if ($livraisonInfo->status != Status::COURIER_DELIVERED) {
        //     $notify[] = ['error', "Vous ne pouvez pas modifier une commande déjà livrée."];
        //     return back()->with($notify);
        // }
        return view('admin.livraison.edit-brouillon', compact('pageTitle', 'livraisonInfo', 'produits', 'magasins','clients','staffs','id'));
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
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.amount'   => 'required|numeric|gt:0',
            'items.*.name'     => 'nullable|string',
            'estimate_date'    => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'payment_status'   => 'required|integer|in:0,1',
        ]);


        $sender                      = auth()->user(); 

        LivraisonProduct::where([['livraison_info_id', $id],['etat',0]])->delete();

        $subTotal = $qtebrouillon = $qterestante = $quantityAcceptee = 0;
        
        $data = [];
        foreach ($request->items as $item) {
             
            $productArray = Produit::where([['categorie_id',$item['produit']],['quantity_restante','>',0]])
                                    ->orderby('id','asc');
            $total = $productArray->sum('quantity_restante');
            
            $productArray = $productArray->get();
            $qterestante = $quantityAcceptee = 0;

            if($productArray !=null)
            {
                if($total>$item['quantity']){
                    $qtebrouillon = 0;
                    $item['quantity'] = $item['quantity'];
                }elseif($total ==$item['quantity']){
                    $qtebrouillon=0;
                    $item['quantity'] = $item['quantity'];
                }else{
                    $qtebrouillon = $item['quantity'] - $total; 
                    $item['quantity'] = $total;
                }
                
            foreach($productArray as $data){

                if($item['quantity']==0){
                    continue;
                }
                 $qteInitial = $data->quantity_restante;
                 $qtecommandee =  $item['quantity'];
                 if($qterestante >0){
                    $qtecommandee = $qterestante;
                 }
                  
                 if($qtecommandee>$qteInitial){
                    $qterestante = $qtecommandee-$qteInitial;
                    $quantityAcceptee = $qteInitial;
                 }elseif($qtecommandee<$qteInitial){
                    $qterestante = ($qtecommandee-$qteInitial)* -1;
                    $quantityAcceptee = $qtecommandee;
                 }else{
                    $qterestante = 0;
                    $quantityAcceptee = $qtecommandee;
                 }
                 
            
            $livraisonProduit = Produit::where('id', $data->id)->first();
            $price = $livraisonProduit->price * $quantityAcceptee;
            $subTotal += $price;

            
            LivraisonProduct::insert([
                'livraison_info_id' => $id,
                'livraison_produit_id' => $livraisonProduit->id,
                'qty'             => $quantityAcceptee, 
                'fee'             => $price,
                'type_price'      => $livraisonProduit->price,
                'created_at'      => now(),
            ]);    
            $livraisonProduit->quantity_restante = $livraisonProduit->quantity_restante - $quantityAcceptee;
            $livraisonProduit->quantity_use = $livraisonProduit->quantity_use + $quantityAcceptee; 
            $livraisonProduit->save();
            $item['quantity'] = $qterestante;
            
         }
         if($qtebrouillon>0){
            $livraisonProduit = Produit::where('categorie_id', $item['produit'])->first();
            $price = $livraisonProduit->price * $qtebrouillon; 
            
            LivraisonProduct::insert([
                'livraison_info_id' => $id,
                'livraison_produit_id' => $livraisonProduit->id,
                'qty'             => $qtebrouillon, 
                'fee'             => $price,
                'type_price'      => $livraisonProduit->price,
                'etat' => 0,
                'created_at'      => now(),
            ]);         
         }
        }else{
            $livraisonProduit = Produit::where('categorie_id', $item['produit'])->first();
            $price = $livraisonProduit->price * $item['quantity']; 
            
            LivraisonProduct::insert([
                'livraison_info_id' => $id,
                'livraison_produit_id' => $livraisonProduit->id,
                'qty'             => $item['quantity'], 
                'fee'             => $price,
                'type_price'      => $livraisonProduit->price,
                'etat' => 0,
                'created_at'      => now(),
            ]);                      
        }

        }

         

        $livraisonPayment                  = LivraisonPayment::where('livraison_info_id',$id)->first();
        $livraisonPayment->amount          = $livraisonPayment->amount + $subTotal;
        $livraisonPayment->final_amount    = ($livraisonPayment->amount- $livraisonPayment->discount) + $livraisonPayment->frais_livraison; 
        $livraisonPayment->save();

        

        $notify[] = ['success', 'La commande en brouillon a été mise à jour avec succès'];
        return to_route('admin.livraison.brouillon.details', encrypt($id))->withNotify($notify);
    }

    public function checkbrouillon(){

        $livraisonInfos = LivraisonInfo::dateFilter()->searchable(['code'])->filter(['status','receiver_magasin_id','sender_magasin_id'])->where([['estimate_date','>=',gmdate('Y-m-d')],['livraison_infos.status',2]])->where(function ($q) { 
            $q->WhereHas('product', function ($myQuery) {
                 $myQuery->where('etat',0); 
            });
        })->orderBy('id', 'DESC')->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->get();

         
        $data = [];
        foreach($livraisonInfos as $livre){
            $id = $livre->id;
            $subTotal = $qtebrouillon = $qterestante = $quantityAcceptee = 0;
            $products = $livre->products->where('etat',0);
            
        foreach ($products as $item) {
            
            $productArray = Produit::where([['categorie_id',$item->produit->categorie_id],['quantity_restante','>',0]])
                                    ->orderby('id','asc');
            $total = $productArray->sum('quantity_restante'); 

            $productArray = $productArray->get(); 
            $qterestante = $quantityAcceptee = 0;

            if($productArray !=null)
            {
                if($total>$item->qty){
                    $qtebrouillon = 0;
                    $item->qty = $item->qty;
                }elseif($total ==$item->qty){
                    $qtebrouillon=0;
                    $item->qty = $item->qty;
                }else{
                    $qtebrouillon = $item->qty - $total; 
                    $item->qty = $total;
                }
                
            foreach($productArray as $data){

                if($item->qty==0){
                    continue;
                }
                 $qteInitial = $data->quantity_restante;
                 $qtecommandee =  $item->qty;
                 if($qterestante >0){
                    $qtecommandee = $qterestante;
                 }
                  
                 if($qtecommandee>$qteInitial){
                    $qterestante = $qtecommandee-$qteInitial;
                    $quantityAcceptee = $qteInitial;
                 }elseif($qtecommandee<$qteInitial){
                    $qterestante = ($qtecommandee-$qteInitial)* -1;
                    $quantityAcceptee = $qtecommandee;
                 }else{
                    $qterestante = 0;
                    $quantityAcceptee = $qtecommandee;
                 }
                 
            
            $livraisonProduit = Produit::where('id', $data->id)->first();
            $price = $livraisonProduit->price * $quantityAcceptee;
            $subTotal += $price;

            
            LivraisonProduct::insert([
                'livraison_info_id' => $id,
                'livraison_produit_id' => $livraisonProduit->id,
                'qty'             => $quantityAcceptee, 
                'fee'             => $price,
                'type_price'      => $livraisonProduit->price,
                'etat' => 1,
                'created_at'      => now(),
            ]);    
            $livraisonProduit->quantity_restante = $livraisonProduit->quantity_restante - $quantityAcceptee;
            $livraisonProduit->quantity_use = $livraisonProduit->quantity_use + $quantityAcceptee; 
            $livraisonProduit->save();
            $item->qty = $qterestante;
            LivraisonProduct::where([['livraison_info_id', $item->id],['livraison_produit_id', $item->livraison_produit_id],['etat',0]])->delete();
         }
         
         if($qtebrouillon>0){
            
            $livraisonProduit = Produit::where('categorie_id', $item->produit->categorie_id)->first();
            $price = $livraisonProduit->price * $qtebrouillon; 
            
            LivraisonProduct::insert([
                'livraison_info_id' => $id,
                'livraison_produit_id' => $livraisonProduit->id,
                'qty'             => $qtebrouillon, 
                'fee'             => $price,
                'type_price'      => $livraisonProduit->price,
                'etat' => 0,
                'created_at'      => now(),
            ]);        
         }
         
        }

        }

         

        $livraisonPayment                  = LivraisonPayment::where('livraison_info_id',$id)->first();
        $livraisonPayment->amount          = $livraisonPayment->amount + $subTotal;
        $livraisonPayment->final_amount    = ($livraisonPayment->amount- $livraisonPayment->discount) + $livraisonPayment->frais_livraison; 
        $livraisonPayment->save();
    }


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
