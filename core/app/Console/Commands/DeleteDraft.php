<?php

namespace App\Console\Commands;

use App\Models\LivraisonInfo;
use App\Models\LivraisonProduct;
use Illuminate\Console\Command;

class DeleteDraft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:draft';

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
        $livraisonInfos = LivraisonInfo::where([['estimate_date','<',gmdate('Y-m-d')]])->where(function ($q) { 
            $q->WhereHas('product', function ($myQuery) {
                 $myQuery->where('etat',0); 
            });
        })->get();
        foreach($livraisonInfos as $item){
            LivraisonProduct::where([['livraison_info_id',$item->id],['etat',0]])->delete();
        }

        return $this->info('Les brouillons ont été supprimés!');;
    }
}
