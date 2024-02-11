<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class LivraisonInfo extends Model
{
    use Searchable, GlobalStatus, PowerJoins;

    public function senderStaff()
    {
        return $this->belongsTo(User::class, 'sender_staff_id');
    }

    public function receiverStaff()
    {
        return $this->belongsTo(User::class, 'receiver_staff_id');
    }
    
    public function receiverClient()
    {
        return $this->belongsTo(Client::class, 'receiver_client_id');
    }

    public function receiverMagasin()
    {
        return $this->belongsTo(Magasin::class, 'receiver_magasin_id');
    }

    public function senderMagasin()
    {
        return $this->belongsTo(Magasin::class, 'sender_magasin_id');
    }

    public function paymentInfo()
    {
        return $this->hasOne(LivraisonPayment::class, 'livraison_info_id');
    }

    public function livraisonDetail()
    {
        return $this->hasMany(LivraisonProduct::class, 'livraison_info_id')->with('produit');
    }

    public function scopeQueue()
    {
        return $this->where('sender_magasin_id', auth()->user()->magasin_id)->where('status', Status::COURIER_QUEUE);
    }

    public function scopeDispatched()
    {
        return $this->where('sender_magasin_id', auth()->user()->magasin_id)->where('status', Status::COURIER_DISPATCH);
    }

    public function scopeUpcoming()
    {
        return $this->where('receiver_magasin_id', auth()->user()->magasin_id)->where('status', Status::COURIER_UPCOMING);
    }

    public function scopeDeliveryQueue()
    {
        return $this->where('receiver_magasin_id', auth()->user()->magasin_id)->where('status', Status::COURIER_DELIVERYQUEUE);
    }

    public function scopeDelivered()
    {
        return $this->where('receiver_magasin_id', auth()->user()->magasin_id)->where('status', Status::COURIER_DELIVERED);
    }

    public function scopeCredit()
    {
        return $this->where('receiver_magasin_id', auth()->user()->magasin_id)->where('status', Status::COURIER_DELIVERED);
    }

    public function products()
    {
        return $this->hasMany(LivraisonProduct::class, 'livraison_info_id', 'id');
    }
    public function paymentList()
    {
        return $this->hasMany(LivraisonPayment::class, 'livraison_info_id', 'id');
    }
    public function payment()
    {
        return $this->hasOne(LivraisonPayment::class, 'livraison_info_id', 'id');
    }

    public function product()
    {
        return $this->hasOne(LivraisonProduct::class, 'livraison_info_id', 'id');
    }
}