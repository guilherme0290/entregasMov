<?php

namespace App\Models;

use App\Enums\CourierPaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourierEarning extends Model
{
    protected $fillable = [
        'company_id',
        'delivery_id',
        'courier_id',
        'gross_amount',
        'fee_amount',
        'net_amount',
        'payment_status',
        'released_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'fee_amount' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'payment_status' => CourierPaymentStatus::class,
            'released_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }
}
