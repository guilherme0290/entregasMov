<?php

namespace App\Models;

use App\Enums\DeliveryRequestSource;
use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'company_id',
        'partner_id',
        'courier_id',
        'created_by_user_id',
        'request_source',
        'pickup_address',
        'pickup_number',
        'pickup_district',
        'pickup_city',
        'pickup_state',
        'pickup_zip_code',
        'pickup_complement',
        'pickup_reference',
        'dropoff_address',
        'dropoff_number',
        'dropoff_district',
        'dropoff_city',
        'dropoff_state',
        'dropoff_zip_code',
        'dropoff_complement',
        'dropoff_reference',
        'recipient_name',
        'recipient_phone',
        'notes',
        'delivery_fee',
        'courier_payout_amount',
        'distance_km',
        'estimated_time_min',
        'status',
        'scheduled_for',
        'accepted_at',
        'pickup_started_at',
        'in_transit_at',
        'delivered_at',
        'canceled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'request_source' => DeliveryRequestSource::class,
            'delivery_fee' => 'decimal:2',
            'courier_payout_amount' => 'decimal:2',
            'distance_km' => 'decimal:2',
            'status' => DeliveryStatus::class,
            'scheduled_for' => 'datetime',
            'accepted_at' => 'datetime',
            'pickup_started_at' => 'datetime',
            'in_transit_at' => 'datetime',
            'delivered_at' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(DeliveryStatusLog::class);
    }

    public function rejections(): HasMany
    {
        return $this->hasMany(DeliveryRejection::class);
    }

    public function earning(): HasOne
    {
        return $this->hasOne(CourierEarning::class);
    }
}
