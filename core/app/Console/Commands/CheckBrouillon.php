<?php

namespace App\Console\Commands;

use App\Models\Produit;
use App\Models\LivraisonInfo;
use Illuminate\Console\Command;
use App\Models\LivraisonPayment;
use App\Models\LivraisonProduct;

class CheckBrouillon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:draft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $livraisonInfos = LivraisonInfo::dateFilter()->searchable(['code'])->filter(['status','receiver_magasin_id','sender_magasin_id'])->where([['estimate_date','>=',gmdate('Y-m-d')],['livraison_infos.status',2]])->where(function ($q) { 
            $q->WhereHas('product', function ($myQuery) {
                 $myQuery->where('etat',0); 
            });
        })->get();

         
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
         LivraisonProduct::where([['id', $item->id],['etat',0]])->delete();
         
        }

        $this->info('Les brouillons ont été vérifiés et réactivés!');

        }

         

        $livraisonPayment                  = LivraisonPayment::where('livraison_info_id',$id)->first();
        $livraisonPayment->amount          = $livraisonPayment->amount + $subTotal;
        $livraisonPayment->final_amount    = ($livraisonPayment->amount- $livraisonPayment->discount) + $livraisonPayment->frais_livraison; 
        $livraisonPayment->save();
    }
    }
}
