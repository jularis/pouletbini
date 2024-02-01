<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class LivraisonProduct extends Model
{

    use GlobalStatus, PowerJoins;

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'livraison_produit_id');
    }
    
    public function info()
    {
        return $this->belongsTo(LivraisonInfo::class, 'livraison_info_id');
    }
}