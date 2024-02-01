<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class LivraisonPayment extends Model
{

    use Searchable, GlobalStatus;
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function info()
    {
        return $this->belongsTo(LivraisonInfo::class, 'livraison_info_id');
    }

    public function magasin()
    {
        return $this->belongsTo(Magasin::class, 'magasin_id');
    }
}