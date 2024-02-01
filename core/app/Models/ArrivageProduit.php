<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class ArrivageProduit extends Model
{
    use Searchable, GlobalStatus, PowerJoins;

    public function arrivage()
    {
        return $this->belongsTo(Arrivage::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}