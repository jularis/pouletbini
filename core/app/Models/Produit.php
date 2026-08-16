<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use GlobalStatus;

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}