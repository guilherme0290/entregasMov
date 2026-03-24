<?php

namespace Tests\Feature\Api;

use App\Enums\UserRole;
use App\Models\Courier;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourierNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_delivery_creates_notification_for_company_couriers(): void
    {
        $partnerUser = User::factory()->create([
            'role' => UserRole::Partner,
        ]);

        $partner = Partner::create([
            'user_id' => $partnerUser->id,
            'company_id' => $partnerUser->company_id,
            'trade_name' => 'Farmacia Central',
            'company_name' => 'Farmacia Central LTDA',
            'tax_id' => '12345678000190',
            'contact_name' => 'Maria',
            'contact_phone' => '(67) 99999-4321',
            'pickup_address' => 'Rua das Flores',
            'pickup_number' => '123',
            'pickup_city' => 'Campo Grande',
            'pickup_state' => 'MS',
            'default_delivery_fee' => 10,
        ]);

        $courierUser = User::factory()->create([
            'role' => UserRole::Courier,
            'company_id' => $partnerUser->company_id,
        ]);

        Courier::create([
            'user_id' => $courierUser->id,
            'company_id' => $partnerUser->company_id,
            'tax_id' => '12345678900',
            'address' => 'Rua A',
            'city' => 'Campo Grande',
            'state' => 'MS',
            'availability_status' => 'offline',
            'is_active' => true,
        ]);

        Sanctum::actingAs($partnerUser);

        $this->postJson('/api/v1/partner/deliveries', [
            'dropoff_address' => 'Rua XV de Novembro',
            'dropoff_number' => '100',
            'dropoff_city' => 'Campo Grande',
            'dropoff_state' => 'MS',
            'recipient_name' => 'Joao',
            'recipient_phone' => '(67) 98888-1111',
            'notes' => 'Entregar com cuidado',
        ])->assertCreated();

        $this->assertDatabaseCount('user_notifications', 1);
        $this->assertDatabaseHas('user_notifications', [
            'company_id' => $partner->company_id,
            'user_id' => $courierUser->id,
            'type' => 'new_delivery',
            'title' => 'Nova entrega disponível',
        ]);

        Sanctum::actingAs($courierUser);

        $notificationsResponse = $this->getJson('/api/v1/courier/notifications')
            ->assertOk()
            ->assertJsonPath('data.meta.unread_count', 1)
            ->assertJsonPath('data.items.0.type', 'new_delivery');

        $notificationId = $notificationsResponse->json('data.items.0.id');

        $this->postJson("/api/v1/courier/notifications/{$notificationId}/read")
            ->assertOk()
            ->assertJsonPath('data.read_at', fn ($value) => filled($value));

        $this->assertDatabaseMissing('user_notifications', [
            'id' => $notificationId,
            'read_at' => null,
        ]);
    }
}
