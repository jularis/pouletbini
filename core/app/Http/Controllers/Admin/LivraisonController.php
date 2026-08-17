<?php

namespace App\Http\Controllers\Admin;

use Excel;
use App\Models\User;
use App\Models\Magasin;
use App\Models\Payment;
use App\Models\LivraisonDeletionHistory;
use App\Models\LivraisonInfo;
use App\Exports\ExportCommandes;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LivraisonController extends Controller
{

    public function livraisonInfo()
    {
        $pageTitle    = "Informations de Livraisons";
        $staffs = User::get();
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

    public function deletionHistory()
    {
        $pageTitle = "Historique des suppressions de commandes";
        $staffs = User::get();
        $magasins = Magasin::get();

        $histories = LivraisonDeletionHistory::dateFilter('deleted_at')
            ->searchable(['code', 'sender_name', 'receiver_name', 'deleted_by_name'])
            ->filter(['receiver_magasin_id', 'sender_magasin_id', 'deleted_by_user_id'])
            ->when(request()->magasin, function ($query, $magasin) {
                $query->where(function ($q) use ($magasin) {
                    $q->where('receiver_magasin_id', $magasin)->orWhere('sender_magasin_id', $magasin);
                });
            })
            ->with('senderMagasin', 'receiverMagasin', 'deletedBy')
            ->orderBy('deleted_at', 'DESC')
            ->paginate(getPaginate());

        return view('admin.livraison.deletion_history', compact('pageTitle', 'histories', 'staffs', 'magasins'));
    }

    public function restoreDeletion($id)
    {
        $history = LivraisonDeletionHistory::findOrFail($id);

        if ($history->restored_at) {
            $notify[] = ['error', 'Cette commande a deja ete restauree.'];
            return back()->withNotify($notify);
        }

        if (LivraisonInfo::where('code', $history->code)->exists()) {
            $notify[] = ['error', 'Une commande avec ce numero existe deja.'];
            return back()->withNotify($notify);
        }

        $payload = json_decode($history->payload, true);

        if (!$payload) {
            $notify[] = ['error', 'Les donnees de restauration sont introuvables.'];
            return back()->withNotify($notify);
        }

        DB::transaction(function () use ($history, $payload) {
            $infoData = $this->tableData($payload, 'livraison_infos');

            if (LivraisonInfo::where('id', @$infoData['id'])->exists()) {
                unset($infoData['id']);
            }

            $livraisonInfoId = DB::table('livraison_infos')->insertGetId($infoData);

            $paymentData = $this->tableData(@$payload['payment_info'] ?? @$payload['payment'] ?? [], 'livraison_payments');

            if ($paymentData) {
                unset($paymentData['id']);
                $paymentData['livraison_info_id'] = $livraisonInfoId;
                DB::table('livraison_payments')->insert($paymentData);
            }

            foreach (@$payload['products'] ?? [] as $product) {
                $productData = $this->tableData($product, 'livraison_products');

                if (!$productData) {
                    continue;
                }

                unset($productData['id']);
                $productData['livraison_info_id'] = $livraisonInfoId;
                DB::table('livraison_products')->insert($productData);
            }

            $history->restored_at = now();
            $history->restored_by_admin_id = auth()->guard('admin')->id();
            $history->save();
        });

        $notify[] = ['success', 'La commande a ete restauree avec succes.'];
        return to_route('admin.livraison.info.index')->withNotify($notify);
    }

    private function tableData($data, $table)
    {
        if (!is_array($data)) {
            return [];
        }

        return array_intersect_key($data, array_flip(Schema::getColumnListing($table)));
    }

    public function exportExcel()
    { 
        $filename = 'commandes-' . gmdate('dmYhms') . '.xlsx';
        return Excel::download(new ExportCommandes, $filename);
    }
}
