<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class LivraisonDeletionHistory extends Model
{
    use Searchable;

    protected $guarded = [];

    protected $casts = [
        'deleted_at'       => 'datetime',
        'order_created_at' => 'datetime',
        'restored_at'      => 'datetime',
    ];

    public function senderMagasin()
    {
        return $this->belongsTo(Magasin::class, 'sender_magasin_id');
    }

    public function receiverMagasin()
    {
        return $this->belongsTo(Magasin::class, 'receiver_magasin_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by_user_id');
    }
}
