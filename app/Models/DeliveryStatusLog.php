<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryStatusLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'delivery_id',
        'previous_status',
        'new_status',
        'user_id',
        'notes',
        'latitude',
        'longitude',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'created_at' => 'datetime',
        ];
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
