<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class Categorie extends Model
{
	use Searchable, GlobalStatus, PowerJoins;

	public function unite()
    {
        return $this->belongsTo(Unite::class);
    }
}