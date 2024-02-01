<?php

namespace App\Http\Controllers\Staff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Magasin;
use App\Models\LivraisonInfo;
use App\Models\LivraisonPayment;
use App\Models\LivraisonProduct;
use App\Models\Produit;
use App\Models\User;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Extension\CommonMark\Node\Block\ListItem;
use Stevebauman\Location\Facades\Location;

class LivraisonController extends Controller
{
    

    public function invoice($id)
    {
        $pageTitle = 'Facture';
        $livraisonInfo = LivraisonInfo::with('products.produit.categorie', 'payment')->findOrFail(decrypt($id));
        return view('staff.invoice', compact('pageTitle', 'livraisonInfo'));
    }

 
    public function sentQueue()
    {
        $pageTitle    = 'En attente';
        $user         = auth()->user();
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code', 'receiverMagasin:name'])->where([['receiver_staff_id', $user->id]])->where('status', Status::COURIER_QUEUE)->orderBy('id', 'DESC')
            ->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('staff.livraison.sentQueue', compact('pageTitle', 'livraisonLists'));
    }

    public function livraisonDispatch()
    {
        $pageTitle    = 'Livraison Expédiée';
        $user         = auth()->user();
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code', 'receiverMagasin:name'])->where([['receiver_staff_id', $user->id]])->where('status', Status::COURIER_DISPATCH)->orderBy('id', 'DESC')
            ->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('staff.livraison.dispatch', compact('pageTitle', 'livraisonLists'));
    }

    public function upcoming()
    {
        $pageTitle    = 'Livraison Encours';
        $user         = auth()->user();
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code'])->where('receiver_magasin_id', $user->magasin_id)->where('status', Status::COURIER_UPCOMING)->orderBy('id', 'DESC')
            ->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('staff.livraison.upcoming', compact('pageTitle', 'livraisonLists'));
    }

    public function dispatched($id)
    {
        $user                = auth()->user();
        $livraisonInfo         = LivraisonInfo::where([['receiver_staff_id', $user->id]])->findOrFail($id);
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

        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code'])->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->where([['receiver_staff_id', $user->id],['status', Status::COURIER_DELIVERYQUEUE]])->orderBy('livraison_infos.id','DESC')->paginate(getPaginate());
      
        return view('staff.livraison.deliveryQueue', compact('pageTitle', 'livraisonLists'));
    }

    public function delivered()
    {
        $pageTitle    = 'Livraison livrée';
        $livraisonLists = $this->livraisons('delivered');
        
        return view('staff.livraison.list', compact('pageTitle', 'livraisonLists'));
    }

    public function credit()
    {
        $pageTitle    = 'Livraison à Crédit';
        $livraisonLists = $this->livraisons('credit');
        $listeId = array();
        if($livraisonLists->count()){
            foreach($livraisonLists as $data){
                $listeId[]=$data->id;
            }
        }
        $listeId = implode(",", $listeId);
       
        $livraisonLists = LivraisonPayment::where('status', Status::UNPAID)
            ->get();
           
        return view('staff.livraison.credit', compact('pageTitle', 'livraisonLists'));
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
        $livraisons = $livraisons->where('receiver_staff_id', $user->id);
        $livraisons = $livraisons->dateFilter()->searchable(['code'])->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->orderBy('livraison_infos.id','DESC')->paginate(getPaginate());
        
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
        $livraisonLists = LivraisonInfo::dateFilter()->searchable(['code', 'receiverMagasin:name'])->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')
            ->where([['receiver_staff_id', $user->id]])->orderBy('id', 'DESC')->paginate(getPaginate());
        
            return view('staff.livraison.list', compact('pageTitle', 'livraisonLists'));
    }

    public function details($id)
    {
        $pageTitle   = 'Details de la Livraison';
        $livraisonInfo = LivraisonInfo::with('products.produit.categorie')->findOrFail(decrypt($id));
        return view('staff.livraison.details', compact('pageTitle', 'livraisonInfo'));
    }

    public function payment(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'montant' => 'required',
        ]);
        $user = auth()->user();

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
        $clientId = $livraison->receiver_client_id;
        $livraison->receiver_staff_id = $user->id;
        $livraison->status            = Status::COURIER_DELIVERED;
        $livraison->save();

$ip = getRealIP();
$currentUserInfo = Location::get($ip); 
       
       $client = Client::find($clientId);
        $client->longitude = $currentUserInfo->longitude;
        $client->latitude = $currentUserInfo->latitude;

        $client->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'Livraison reçue ' . $user->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
        $adminNotification->save();

        $notify[] = ['success', 'Reception terminée'];
        $code = $request->code;
        $payment = LivraisonPayment::where('livraison_info_id', $livraison->id)->first();
        // if($payment->status==1){
        //     return back()->withNotify($notify);
        // }else{
        //     return back()->with(compact('code'))->withNotify($notify);
        // }
        return back()->withNotify($notify);
        
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
        return view('staff.livraison.cash', compact('pageTitle', 'magasinIncomeLists'));
    }

    public function sentLivraisonList()
    {
        $user = auth()->user();
        $pageTitle = 'Total Livraison Expédiée';
        $livraisonInfo = LivraisonInfo::dateFilter()->searchable(['code']);
        $livraisonLists = $livraisonInfo->where('sender_staff_id', $user->id)->whereIn('status', [Status::COURIER_DISPATCH, Status::COURIER_DELIVERYQUEUE, Status::COURIER_DELIVERED])->orderBy('id', 'DESC')
            ->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return view('staff.livraison.list', compact('pageTitle', 'livraisonLists'));
    }

    public function receivedLivraisonList()
    {
        $user = auth()->user();
        $pageTitle = 'Liste des Livraisons reçues';
        $livraisonLists = LivraisonInfo::where('receiver_staff_id', $user->id)->orderBy('id', 'DESC')->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')
            ->paginate(getPaginate());
        return view('staff.livraison.list', compact('pageTitle', 'livraisonLists'));
    }
}
