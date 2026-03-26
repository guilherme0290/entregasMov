<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryTransfer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'delivery_id',
        'previous_courier_id',
        'new_courier_id',
        'transferred_by_user_id',
        'reason',
        'notes',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function previousCourier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'previous_courier_id');
    }

    public function newCourier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'new_courier_id');
    }

    public function transferredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_by_user_id');
    }
}
