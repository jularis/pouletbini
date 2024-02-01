<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Searchable, GlobalStatus;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native produits.
     *
     * @var array
     */


    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }


    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->lastname . ' ' . $this->firstname,
        );
    }

    public function magasin()
    {
        return $this->belongsTo(Magasin::class, 'magasin_id');
    }


    // SCOPES
  

    public function scopeBanned()
    {
        return $this->where('status', Status::BAN_USER);
    }
    public function scopeManager($query)
    {
        $query->where('user_type', 'manager');
    }
    public function scopeStaff($query)
    {
        $query->where('user_type', 'staff');
    }
}
