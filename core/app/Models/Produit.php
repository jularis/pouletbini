<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Produit extends Model
{
    use Searchable, GlobalStatus, PowerJoins;

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function arrivage()
    {
        return $this->belongsTo(Arrivage::class);
    }
}