<?php

namespace App\Http\Controllers\Admin;

use Excel;
use App\Models\User;
use App\Models\Magasin;
use App\Models\Payment;
use App\Models\LivraisonInfo;
use App\Exports\ExportCommandes;
use App\Http\Controllers\Controller;

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

    public function livraisonDetail($id)
    {
        $livraisonInfo = LivraisonInfo::with('products.produit.categorie', 'payment')->findOrFail($id);
        $pageTitle   = "Details de Livraison: " . $livraisonInfo->code;
        return view('admin.livraison.details', compact('pageTitle', 'livraisonInfo'));
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
}
