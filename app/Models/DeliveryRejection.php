<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryRejection extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'delivery_id',
        'courier_id',
        'reason',
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

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }
}
