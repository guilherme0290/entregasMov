<?php

namespace Tests\Feature\Web;

use App\Enums\UserRole;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_can_access_portal_and_create_delivery(): void
    {
        $user = User::factory()->create([
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

        $this->actingAs($user)
            ->get('/partner?tab=new')
            ->assertOk()
            ->assertSee('Solicitar Nova Entrega');

        $this->actingAs($user)
            ->post('/partner/deliveries', [
                'dropoff_address' => 'Rua XV de Novembro',
                'dropoff_number' => '100',
                'dropoff_city' => 'Campo Grande',
                'dropoff_state' => 'MS',
                'recipient_name' => 'Joao',
                'delivery_fee' => 10,
            ])
            ->assertRedirect(route('partner.portal', ['tab' => 'progress']));

        $this->assertDatabaseCount('deliveries', 1);
    }
}
