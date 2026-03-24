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

class AdminAssignCourierTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_courier_to_pending_delivery(): void
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
            'is_active' => true,
        ]);

        $delivery = Delivery::create([
            'code' => 'del-000001',
            'company_id' => $admin->company_id,
            'partner_id' => $partner->id,
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
            'status' => DeliveryStatus::Pending,
        ]);

        Sanctum::actingAs($admin);

        $this->postJson("/api/v1/admin/deliveries/{$delivery->id}/assign-courier", [
            'courier_id' => $courier->id,
        ])
            ->assertOk()
            ->assertJsonPath('data.courier_id', $courier->id)
            ->assertJsonPath('data.status', 'accepted');

        $this->assertDatabaseHas('delivery_status_logs', [
            'delivery_id' => $delivery->id,
            'new_status' => 'accepted',
        ]);
    }
}
