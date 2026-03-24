<?php

namespace Tests\Feature\Api;

use App\Enums\CourierAvailabilityStatus;
use App\Enums\DeliveryStatus;
use App\Enums\UserRole;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourierEarningsFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_delivery_generates_earning_from_courier_payout_amount(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $partnerUser = User::factory()->create([
            'role' => UserRole::Partner,
            'company_id' => $admin->company_id,
        ]);
        $partner = Partner::create([
            'user_id' => $partnerUser->id,
            'company_id' => $admin->company_id,
            'trade_name' => 'Farmacia Central',
            'company_name' => 'Farmacia Central LTDA',
            'tax_id' => '12345678000190',
            'contact_name' => 'Maria',
            'contact_phone' => '(67) 99999-4321',
            'pickup_address' => 'Rua das Flores',
            'pickup_number' => '123',
            'pickup_city' => 'Campo Grande',
            'pickup_state' => 'MS',
            'default_delivery_fee' => 20,
        ]);

        $courierUser = User::factory()->create([
            'role' => UserRole::Courier,
            'company_id' => $admin->company_id,
        ]);
        $courier = Courier::create([
            'user_id' => $courierUser->id,
            'company_id' => $admin->company_id,
            'tax_id' => '12345678900',
            'address' => 'Rua A',
            'city' => 'Campo Grande',
            'state' => 'MS',
            'availability_status' => CourierAvailabilityStatus::Online,
        ]);

        $delivery = Delivery::create([
            'code' => 'del-000001',
            'company_id' => $admin->company_id,
            'partner_id' => $partner->id,
            'courier_id' => $courier->id,
            'created_by_user_id' => $admin->id,
            'request_source' => 'manual',
            'pickup_address' => 'Rua das Flores',
            'pickup_city' => 'Campo Grande',
            'pickup_state' => 'MS',
            'dropoff_address' => 'Rua XV de Novembro',
            'dropoff_city' => 'Campo Grande',
            'dropoff_state' => 'MS',
            'delivery_fee' => 20,
            'courier_payout_amount' => 12,
            'status' => DeliveryStatus::InTransit,
        ]);

        Sanctum::actingAs($courierUser);

        $this->postJson("/api/v1/courier/deliveries/{$delivery->id}/complete")
            ->assertOk()
            ->assertJsonPath('data.status', 'delivered');

        $this->assertDatabaseHas('courier_earnings', [
            'company_id' => $admin->company_id,
            'delivery_id' => $delivery->id,
            'courier_id' => $courier->id,
            'gross_amount' => '12.00',
            'net_amount' => '12.00',
        ]);
    }
}
