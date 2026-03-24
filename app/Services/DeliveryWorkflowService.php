<?php

namespace App\Services;

use App\Enums\CourierAvailabilityStatus;
use App\Enums\DeliveryRequestSource;
use App\Enums\DeliveryStatus;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\DeliveryRejection;
use App\Models\DeliveryStatusLog;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeliveryWorkflowService
{
    public function __construct(private readonly CourierNotificationService $courierNotificationService) {}

    public function createForPartner(Partner $partner, User $user, array $data): Delivery
    {
        return DB::transaction(function () use ($partner, $user, $data) {
            $delivery = Delivery::create([
                'code' => $this->generateCode(),
                'company_id' => $partner->company_id,
                'partner_id' => $partner->id,
                'created_by_user_id' => $user->id,
                'request_source' => $data['request_source'] ?? DeliveryRequestSource::PartnerWeb,
                'pickup_address' => $data['pickup_address'] ?? $partner->pickup_address,
                'pickup_number' => $data['pickup_number'] ?? $partner->pickup_number,
                'pickup_district' => $data['pickup_district'] ?? $partner->pickup_district,
                'pickup_city' => $data['pickup_city'] ?? $partner->pickup_city,
                'pickup_state' => $data['pickup_state'] ?? $partner->pickup_state,
                'pickup_zip_code' => $data['pickup_zip_code'] ?? $partner->pickup_zip_code,
                'pickup_complement' => $data['pickup_complement'] ?? $partner->pickup_complement,
                'pickup_reference' => $data['pickup_reference'] ?? null,
                'dropoff_address' => $data['dropoff_address'],
                'dropoff_number' => $data['dropoff_number'] ?? null,
                'dropoff_district' => $data['dropoff_district'] ?? null,
                'dropoff_city' => $data['dropoff_city'],
                'dropoff_state' => $data['dropoff_state'],
                'dropoff_zip_code' => $data['dropoff_zip_code'] ?? null,
                'dropoff_complement' => $data['dropoff_complement'] ?? null,
                'dropoff_reference' => $data['dropoff_reference'] ?? null,
                'recipient_name' => $data['recipient_name'] ?? null,
                'recipient_phone' => $data['recipient_phone'] ?? null,
                'notes' => $data['notes'] ?? null,
                'delivery_fee' => $data['delivery_fee'] ?? $partner->default_delivery_fee,
                'courier_payout_amount' => $data['courier_payout_amount'] ?? $data['delivery_fee'] ?? $partner->default_delivery_fee,
                'distance_km' => $data['distance_km'] ?? null,
                'estimated_time_min' => $data['estimated_time_min'] ?? null,
                'status' => DeliveryStatus::Pending,
                'scheduled_for' => $data['scheduled_for'] ?? null,
            ]);

            $this->log($delivery, null, DeliveryStatus::Pending, $user);
            $this->courierNotificationService->notifyNewDelivery($delivery->loadMissing('partner'));

            return $delivery;
        });
    }

    public function accept(Delivery $delivery, Courier $courier, User $user): Delivery
    {
        abort_unless($courier->is_active, 422, 'Entregador inativo.');
        abort_unless($courier->availability_status->value === 'online', 422, 'Entregador precisa estar online.');
        abort_unless(in_array($delivery->status->value, [DeliveryStatus::Pending->value, DeliveryStatus::Available->value], true), 422, 'Entrega não está disponível.');
        abort_if($delivery->courier_id !== null, 422, 'Entrega já possui entregador.');

        return DB::transaction(function () use ($delivery, $courier, $user) {
            $previousStatus = $delivery->status;

            $delivery->update([
                'courier_id' => $courier->id,
                'status' => DeliveryStatus::Accepted,
                'accepted_at' => now(),
            ]);

            $courier->update([
                'availability_status' => CourierAvailabilityStatus::Busy,
                'last_status_at' => now(),
            ]);

            $this->log($delivery, $previousStatus, DeliveryStatus::Accepted, $user);

            return $delivery->fresh(['partner', 'courier.user', 'statusLogs']);
        });
    }

    public function assignCourier(Delivery $delivery, Courier $courier, User $user): Delivery
    {
        abort_unless($courier->is_active, 422, 'Entregador inativo.');
        abort_unless(in_array($delivery->status->value, [DeliveryStatus::Pending->value, DeliveryStatus::Available->value], true), 422, 'Entrega não pode receber atribuição manual neste status.');

        return DB::transaction(function () use ($delivery, $courier, $user) {
            $previousStatus = $delivery->status;

            $delivery->update([
                'courier_id' => $courier->id,
                'status' => DeliveryStatus::Accepted,
                'accepted_at' => now(),
            ]);

            if ($courier->availability_status->value === 'online') {
                $courier->update([
                    'availability_status' => CourierAvailabilityStatus::Busy,
                    'last_status_at' => now(),
                ]);
            }

            $this->log($delivery, $previousStatus, DeliveryStatus::Accepted, $user, 'Entregador atribuído manualmente pelo admin.');

            return $delivery->fresh(['partner', 'courier.user', 'statusLogs.user', 'earning']);
        });
    }

    public function reject(Delivery $delivery, Courier $courier, User $user, ?string $reason = null): Delivery
    {
        abort_unless(in_array($delivery->status->value, [DeliveryStatus::Pending->value, DeliveryStatus::Available->value], true), 422, 'Entrega não pode ser recusada.');

        return DB::transaction(function () use ($delivery, $courier, $user, $reason) {
            DeliveryRejection::create([
                'delivery_id' => $delivery->id,
                'courier_id' => $courier->id,
                'reason' => $reason,
            ]);

            $this->log($delivery, $delivery->status, $delivery->status, $user, $reason ?: 'Entrega recusada.');

            return $delivery->fresh(['partner', 'rejections']);
        });
    }

    public function startPickup(Delivery $delivery, Courier $courier, User $user): Delivery
    {
        abort_unless($delivery->courier_id === $courier->id, 403, 'Entrega não pertence ao entregador.');
        abort_unless($delivery->status === DeliveryStatus::Accepted, 422, 'Entrega precisa estar aceita.');

        return DB::transaction(function () use ($delivery, $user) {
            $previousStatus = $delivery->status;

            $delivery->update([
                'status' => DeliveryStatus::InPickup,
                'pickup_started_at' => now(),
            ]);

            $this->log($delivery, $previousStatus, DeliveryStatus::InPickup, $user);

            return $delivery->fresh(['partner', 'courier.user', 'statusLogs']);
        });
    }

    public function startTransit(Delivery $delivery, Courier $courier, User $user): Delivery
    {
        abort_unless($delivery->courier_id === $courier->id, 403, 'Entrega não pertence ao entregador.');
        abort_unless($delivery->status === DeliveryStatus::InPickup, 422, 'Entrega precisa estar em coleta.');

        return DB::transaction(function () use ($delivery, $user) {
            $previousStatus = $delivery->status;

            $delivery->update([
                'status' => DeliveryStatus::InTransit,
                'in_transit_at' => now(),
            ]);

            $this->log($delivery, $previousStatus, DeliveryStatus::InTransit, $user);

            return $delivery->fresh(['partner', 'courier.user', 'statusLogs']);
        });
    }

    public function complete(Delivery $delivery, Courier $courier, User $user): Delivery
    {
        abort_unless($delivery->courier_id === $courier->id, 403, 'Entrega não pertence ao entregador.');
        abort_unless(in_array($delivery->status->value, [DeliveryStatus::InPickup->value, DeliveryStatus::InTransit->value], true), 422, 'Entrega não pode ser finalizada.');

        return DB::transaction(function () use ($delivery, $courier, $user) {
            $previousStatus = $delivery->status;

            $delivery->update([
                'status' => DeliveryStatus::Delivered,
                'delivered_at' => now(),
            ]);

            $courier->update([
                'availability_status' => CourierAvailabilityStatus::Online,
                'last_status_at' => now(),
            ]);

            $delivery->earning()->create([
                'company_id' => $delivery->company_id,
                'courier_id' => $courier->id,
                'gross_amount' => $delivery->courier_payout_amount ?? $delivery->delivery_fee,
                'net_amount' => $delivery->courier_payout_amount ?? $delivery->delivery_fee,
            ]);

            $this->log($delivery, $previousStatus, DeliveryStatus::Delivered, $user);

            return $delivery->fresh(['partner', 'courier.user', 'statusLogs', 'earning']);
        });
    }

    public function cancel(Delivery $delivery, User $user, ?string $reason = null): Delivery
    {
        abort_unless(in_array($delivery->status->value, [
            DeliveryStatus::Pending->value,
            DeliveryStatus::Available->value,
            DeliveryStatus::Accepted->value,
        ], true), 422, 'Entrega não pode ser cancelada neste status.');

        return DB::transaction(function () use ($delivery, $user, $reason) {
            $previousStatus = $delivery->status;

            $delivery->update([
                'status' => DeliveryStatus::Canceled,
                'canceled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            $this->log($delivery, $previousStatus, DeliveryStatus::Canceled, $user, $reason);

            return $delivery->fresh(['partner', 'courier.user', 'statusLogs']);
        });
    }

    private function log(Delivery $delivery, ?DeliveryStatus $previousStatus, DeliveryStatus $newStatus, User $user, ?string $notes = null): void
    {
        DeliveryStatusLog::create([
            'delivery_id' => $delivery->id,
            'previous_status' => $previousStatus?->value,
            'new_status' => $newStatus->value,
            'user_id' => $user->id,
            'notes' => $notes,
        ]);
    }

    private function generateCode(): string
    {
        return 'del-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT).Str::lower(Str::random(1));
    }
}
