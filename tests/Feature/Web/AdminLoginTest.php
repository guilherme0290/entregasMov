<?php

namespace Tests\Feature\Web;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_through_web_form(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@entregalog.test',
            'phone' => '(67) 99999-0001',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->post('/login', [
            'login' => 'admin@entregalog.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_admin_dashboard_renders_with_updated_navigation(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('EntregasMov')
            ->assertSee('Painel Geral')
            ->assertSee('Relatórios')
            ->assertSee('Visão Geral');
    }
}
