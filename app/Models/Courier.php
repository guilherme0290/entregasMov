<?php

namespace App\Models;

use App\Enums\CourierAvailabilityStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'tax_id',
        'birth_date',
        'address',
        'number',
        'district',
        'city',
        'state',
        'zip_code',
        'complement',
        'notes',
        'vehicle_type',
        'vehicle_model',
        'vehicle_plate',
        'document_photo',
        'driver_license_photo',
        'availability_status',
        'current_latitude',
        'current_longitude',
        'last_status_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'availability_status' => CourierAvailabilityStatus::class,
            'current_latitude' => 'decimal:7',
            'current_longitude' => 'decimal:7',
            'last_status_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function rejections(): HasMany
    {
        return $this->hasMany(DeliveryRejection::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(CourierEarning::class);
    }

    #[Scope]
    protected function available(Builder $query): void
    {
        $query->where('is_active', true)
            ->where('availability_status', CourierAvailabilityStatus::Online);
    }
}
