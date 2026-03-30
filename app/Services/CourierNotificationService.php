<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Delivery;
use App\Models\Courier;
use App\Models\User;
use App\Models\UserNotification;

class CourierNotificationService
{
    public function notifyNewDelivery(Delivery $delivery): void
    {
        $courierUsers = User::query()
            ->where('company_id', $delivery->company_id)
            ->where('role', UserRole::Courier)
            ->where('is_active', true)
            ->whereHas('courier', fn ($query) => $query->where('is_active', true))
            ->get();

        if ($courierUsers->isEmpty()) {
            return;
        }

        $rows = $courierUsers->map(fn (User $user) => [
            'company_id' => $delivery->company_id,
            'user_id' => $user->id,
            'delivery_id' => $delivery->id,
            'type' => 'new_delivery',
            'title' => 'Nova entrega disponível',
            'message' => 'Nova entrega de '.$delivery->partner->trade_name.' para '.$delivery->dropoff_address.'.',
            'payload' => json_encode([
                'delivery_id' => $delivery->id,
                'delivery_code' => $delivery->code,
                'partner_id' => $delivery->partner_id,
                'partner_name' => $delivery->partner->trade_name,
                'dropoff_address' => $delivery->dropoff_address,
                'dropoff_city' => $delivery->dropoff_city,
                'delivery_fee' => $delivery->delivery_fee,
                'courier_payout_amount' => $delivery->courier_payout_amount,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        UserNotification::insert($rows);
    }

    public function notifyTransferredDelivery(Delivery $delivery, Courier $courier, string $reason): void
    {
        $courierUser = $courier->loadMissing('user')->user;

        if (! $courierUser || ! $courierUser->is_active) {
            return;
        }

        UserNotification::create([
            'company_id' => $delivery->company_id,
            'user_id' => $courierUser->id,
            'delivery_id' => $delivery->id,
            'type' => 'delivery_transfer',
            'title' => 'Entrega transferida para voce',
            'message' => 'A entrega '.$delivery->code.' foi transferida para voce. Motivo: '.$reason.'.',
            'payload' => [
                'delivery_id' => $delivery->id,
                'delivery_code' => $delivery->code,
                'partner_id' => $delivery->partner_id,
                'partner_name' => $delivery->partner->trade_name,
                'status' => $delivery->status->value,
                'reason' => $reason,
            ],
        ]);
    }
}
