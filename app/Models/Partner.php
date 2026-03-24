<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'trade_name',
        'company_name',
        'tax_id',
        'contact_name',
        'contact_phone',
        'billing_email',
        'pickup_address',
        'pickup_number',
        'pickup_district',
        'pickup_city',
        'pickup_state',
        'pickup_zip_code',
        'pickup_complement',
        'default_delivery_fee',
        'urgent_delivery_fee',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_delivery_fee' => 'decimal:2',
            'urgent_delivery_fee' => 'decimal:2',
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

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
