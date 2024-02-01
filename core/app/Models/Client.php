<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;
use App\Traits\Searchable;

class Client extends Model
{
    use Searchable, GlobalStatus, PowerJoins;
 
}