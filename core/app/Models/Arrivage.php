<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Arrivage extends Model
{
    use Searchable, GlobalStatus, PowerJoins;

    public function bande()
    {
        return $this->belongsTo(Bande::class);
    }

    public function arrivageDetail()
    {
        return $this->hasMany(ArrivageProduit::class, 'arrivage_id');
    }

    
}