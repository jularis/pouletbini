<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User; 
use App\Models\Client;
use App\Models\Campagne;
use App\Constants\Status;
use App\Models\Cooperative;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LivraisonInfo;
use App\Models\LivraisonPayment;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    // methode d'authentification
 
    
    public function getUpdateapp(){

        $donnees = DB::table('updateapps')->orderby('id','DESC')->select('version','url')->first();
        return response()->json($donnees , 201);
    }

    public function updateInfo(Request $request){

        if($request->user_id){
            $staff   = User::where('id', $request->user_id)->first(); 
        if($staff !=null){
            $staff->firstname = $request->firstname;
            $staff->lastname  = $request->lastname; 
            $staff->email     = $request->email;
            $staff->mobile    = $request->mobile;
            $staff->address    = $request->address; 
            $staff->password  = $request->password ? Hash::make($request->password) : $staff->password;
            $staff->save();
            return response()->json([ 
            'results' => $staff->id, 
            'status_code' => 200, 
            ]);
        }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Cet utilisateur n\'a pas été trouvé.', 
                ]);
        }
       

        }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Vous n\'est pas autorisé à modifier les informations de cet utilisateur.', 
                ]);
        }
        
    }

    public function getCommandes(Request $request){

        if($request->user_id){
            $livraisonLists = LivraisonInfo::where('receiver_staff_id', $request->user_id) 
            ->with('paymentInfo') 
            ->with('receiverMagasin')
            ->with('receiverClient')
            ->with('livraisonDetail')  
            ->where(function ($q) { 
                $q->WhereHas('livraisonDetail', function ($myQuery) {
                     $myQuery->where('etat',1); 
                });
            })
           ->orderBy('estimate_date', 'DESC')
           ->get();

           if($livraisonLists->count()){
            return response()->json([ 
                'results' => $livraisonLists, 
                'status_code' => 200, 
                ]);
           }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Aucune commande.', 
                ]);
           }
        }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Vous n\'est pas autorisé à voir les informations.', 
                ]); 
        }
    }
    
    public function getCommandesConfirm(Request $request){

        if($request->user_id){
            $livraisonLists = LivraisonInfo::where('receiver_staff_id', $request->user_id)
            ->with('paymentInfo')
            ->with('receiverMagasin')
            ->with('receiverClient')
            ->with('livraisonDetail')  
            ->where('status',3)
            ->where(function ($q) {
                $q->OrWhereHas('payment', function ($myQuery) {
                    
                        $myQuery->where('status',1);
                    
                });
            }) 
           ->orderBy('estimate_date', 'ASC')
           ->get();

           if($livraisonLists->count()){
            return response()->json([ 
                'results' => $livraisonLists, 
                'status_code' => 200, 
                ]);
           }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Aucune commande.', 
                ]);
           }
        }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Vous n\'est pas autorisé à voir les informations.', 
                ]); 
        }
    }
    
    public function getCommandesCredit(Request $request){

        if($request->user_id){
            $livraisonLists = LivraisonInfo::where('receiver_staff_id', $request->user_id)
            ->with('paymentInfo')
            ->with('receiverMagasin')
            ->with('receiverClient')
            ->with('livraisonDetail')  
            ->where('status',3)
            ->where(function ($q) {
                $q->OrWhereHas('payment', function ($myQuery) {
                    
                $myQuery->where('status',0);
                    
                });
            }) 
           ->orderBy('id', 'DESC')
           ->get();

           if($livraisonLists->count()){
            return response()->json([ 
                'results' => $livraisonLists, 
                'status_code' => 200, 
                ]);
           }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Aucune commande.', 
                ]);
           }
        }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Vous n\'est pas autorisé à voir les informations.', 
                ]); 
        }
    }

    public function getCommandesAnnulee(Request $request){

        if($request->user_id){
            $livraisonLists = LivraisonInfo::where('receiver_staff_id', $request->user_id)
            ->with('paymentInfo')
            ->with('receiverMagasin')
            ->with('receiverClient')
            ->with('livraisonDetail')
            ->where('status',1) 
           ->orderBy('id', 'DESC')
           ->get();

           if($livraisonLists->count()){
            return response()->json([ 
                'results' => $livraisonLists, 
                'status_code' => 200, 
                ]);
           }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Aucune commande.', 
                ]);
           }
        }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Vous n\'est pas autorisé à voir les informations.', 
                ]); 
        }
    }

    public function updateCommandeLivraison(Request $request){

        if($request->user_id){
        $user = User::find($request->user_id);
        $livraison = LivraisonInfo::where([['id', $request->commande_id],['receiver_staff_id', $request->user_id]])->where('status', Status::COURIER_DELIVERYQUEUE)->first();
        if($livraison !=null){
 
            $livraison->receiver_staff_id = $request->user_id;
            $livraison->commentaire = $request->commentaire;
            $livraison->status = Status::COURIER_DELIVERED;
            $livraison->save(); 
            $adminNotification = new AdminNotification();
            $adminNotification->user_id   = $request->user_id;
            $adminNotification->title     = 'Livraison terminée ' . $user->username;
            $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
            $adminNotification->save();
            return response()->json([ 
                'results' => $livraison->id, 
                'status_code' => 200, 
                ]);
        }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Aucune commande.', 
                ]);
        }
        
        }else{
return response()->json([
                'status_code' => 500,
                'message' => 'Vous n\'est pas autorisé à voir les informations.', 
                ]); 
        }
    }

public function updateClient(Request $request){
     if($request->client_id){
    $client = Client::find($request->client_id);  
            $client->longitude = $request->longitude;
            $client->latitude = $request->latitude;
            $client->save(); 
         return response()->json([ 
                'results' => $client->id, 
                'status_code' => 200, 
                ]);
        }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Aucun client', 
                ]);
        } 
    
}
    public function updateCommandePaiement(Request $request){

        if($request->user_id){
        $user = User::find($request->user_id);

        $livraisonPayment = LivraisonPayment::where('livraison_info_id', $request->commande_id)->first();
          
    if($livraisonPayment !=null){
        
        $livraisonPayment->receiver_id = $user->id;
        $livraisonPayment->magasin_id   = $user->magasin_id;
        $livraisonPayment->date        = Carbon::now();

        if(isset($request->montant) && $request->montant>0)
        {
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
        }else{
            $livraisonPayment->partial_amount = $livraisonPayment->final_amount;
            $livraisonPayment->status      = Status::PAID;
        }
        

        
        $livraisonPayment->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'Paiement Livraison ' . $user->username;
        $adminNotification->click_url = urlPath('admin.livraison.info.details', $request->commande_id);
        $adminNotification->save();
        return response()->json([ 
            'results' => $request->commande_id, 
            'status_code' => 200, 
            ]);
    }else{
        return response()->json([
            'status_code' => 500,
            'message' => 'Cette commande n\'existe pas.', 
            ]);
        }

        }else{
            return response()->json([
                            'status_code' => 500,
                            'message' => 'Vous n\'est pas autorisé à voir les informations.', 
                            ]); 
                    }
    }

    public function updateCommandeAnnulation(Request $request){

        if($request->user_id){
        $user = User::find($request->user_id);
        $livraison = LivraisonInfo::where([['id', $request->commande_id],['receiver_staff_id', $request->user_id]])->where('status', Status::COURIER_DELIVERYQUEUE)->first();
        if($livraison !=null){

            
            $livraison->receiver_staff_id = $request->user_id;
            $livraison->commentaire = $request->commentaire;
            $livraison->status = Status::COURIER_CANCEL;
            $livraison->save();
     
            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $request->user_id;
            $adminNotification->title     = 'Commande annulée ' . $user->username;
            $adminNotification->click_url = urlPath('admin.livraison.info.details', $livraison->id);
            $adminNotification->save();
            return response()->json([ 
                'results' => $livraison->id, 
                'status_code' => 200, 
                ]);
        }else{
            return response()->json([
                'status_code' => 500,
                'message' => 'Aucune commande.', 
                ]);
        }
        
        }else{
return response()->json([
                'status_code' => 500,
                'message' => 'Vous n\'est pas autorisé à voir les informations.', 
                ]); 
        }
    }
    public function connexion(Request $request)
    {
	 
        try {

            $request->validate([
            'username' => 'required',
            'password' => 'required'
            ]);
    
            
            if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Désolé, mot de passe non reconnu. Avez-vous oublié vos identifiants? Merci de contacter l\'administrateur.'
            ]);
            }
            
            $user = User::active()->where('username', $request->input('username'))->first();

            if($user)
            {
                 
                $tokenResult = $user->createToken('authToken')->plainTextToken;
                return response()->json([ 
                    'results' => $user, 
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Jularis',
                ]);

            }else{
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Ce compte n\'existe pas ou a été désactivé par l\'administrateur. Veuillez contacter votre administrateur pour rétablir votre compte.'
                ]);
            }
            
            
        } catch (Exception $error) {
            return response()->json([
            'status_code' => 500,
            'message' => 'Error in Login',
            'error' => $error,
            ]);
        }
    } 
}
