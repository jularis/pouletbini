<?php

namespace App\Exports;
use App\Models\User;
use App\Models\LivraisonInfo;
use App\Models\LivraisonProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView; 
use Maatwebsite\Excel\Concerns\Exportable; 
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class UsersCommandesExport implements FromView, WithTitle
{
    use Exportable;
    private $fullname=""; 
    private $id;
    
    public function __construct(int $user_id)
    {
    if($user_id){
    $this->id=$user_id;
    $user = User::find($user_id);
        $this->fullname = $user->lastname.'_'.$user->firstname; 
        }
    }

    public function view(): View
    {
         
        $query = LivraisonProduct::joinRelationship('produit.categorie','info')->joinRelationship('info')
                ->when(request()->status, function ($query, $status) {
                        $query->where('livraison_infos.status',$status); 
                })
                ->when(request()->payment_status, function ($query, $payment_status) {
                    $query->where('livraison_payments.status',$payment_status); 
                })
                ->when(request()->search, function ($query, $search) {
                    $query->where('code',$search); 
                })
                ->when(request()->etat, function ($query, $etat) {
                    $query->where('etat',$etat); 
                })
                ->when($this->id, function ($query, $staff) {
                    $query->where('receiver_staff_id',$staff); 
                })
                ->when(request()->magasin, function ($query, $magasin) {
                    $query->where('receiver_magasin_id',$magasin);
                })
                ->when(request()->date, function ($query, $date) {
                    $date      = explode('-', $date); 
                    $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d'); 
                    $endDate = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;
                     
                    $query->where('estimate_date', '>=', $startDate)->where('estimate_date', '<=', $endDate);
                })
                ->select('livraison_products.*')
                ->orderBy('estimate_date','asc')
                ->orderBy('receiver_client_id','asc');

        return view('admin.livraison.UsersCommandesAllExcel', [
            'commandes' => $query->get()
        ]);
    }
    
    public function title(): string
    {
        Return "Commandes par Livreur ".$this->fullname;
    }
}
