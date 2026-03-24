<?php

namespace Tests\Feature\Api;

use App\Enums\UserRole;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndPartnerDeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_can_login_and_create_a_delivery(): void
    {
        $user = User::factory()->create([
            'name' => 'Parceiro Teste',
            'email' => 'parceiro@teste.com',
            'phone' => '(67) 99999-1234',
            'password' => Hash::make('secret123'),
            'role' => UserRole::Partner,
        ]);

        Partner::create([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
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

        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'login' => 'parceiro@teste.com',
            'password' => 'secret123',
        ]);

        $loginResponse
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'token',
                    'token_type',
                ],
            ]);

        $token = $loginResponse->json('data.token');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/partner/deliveries', [
                'dropoff_address' => 'Rua XV de Novembro',
                'dropoff_number' => '100',
                'dropoff_city' => 'Campo Grande',
                'dropoff_state' => 'MS',
                'recipient_name' => 'Joao',
                'recipient_phone' => '(67) 98888-1111',
                'notes' => 'Entregar com cuidado',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseCount('deliveries', 1);
        $this->assertDatabaseCount('delivery_status_logs', 1);
    }
}
