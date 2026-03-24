<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $company = Company::factory()->create([
            'name' => 'EntregaLOG Operacao',
            'trade_name' => 'EntregaLOG',
            'tax_id' => '00000000000100',
            'email' => 'contato@entregalog.test',
        ]);

        User::factory()->create([
            'company_id' => $company->id,
            'name' => 'Administrador',
            'email' => 'admin@entregalog.test',
            'phone' => '(67) 99999-0001',
            'role' => UserRole::Admin,
        ]);
    }
}
