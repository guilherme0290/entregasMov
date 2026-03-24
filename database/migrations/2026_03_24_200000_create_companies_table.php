<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('trade_name')->nullable();
                $table->string('tax_id', 20)->nullable()->unique();
                $table->string('phone', 20)->nullable();
                $table->string('email')->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasColumn('users', 'company_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('partners', 'company_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('couriers', 'company_id')) {
            Schema::table('couriers', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('deliveries', 'company_id')) {
            Schema::table('deliveries', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('courier_earnings', 'company_id')) {
            Schema::table('courier_earnings', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        $companyId = DB::table('companies')->value('id') ?: DB::table('companies')->insertGetId([
            'name' => 'Operação Principal',
            'trade_name' => 'Operação Principal',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->whereNull('company_id')->update(['company_id' => $companyId]);
        DB::table('partners')->whereNull('company_id')->update(['company_id' => $companyId]);
        DB::table('couriers')->whereNull('company_id')->update(['company_id' => $companyId]);
        DB::table('deliveries')->whereNull('company_id')->update(['company_id' => $companyId]);
        DB::table('courier_earnings')->whereNull('company_id')->update(['company_id' => $companyId]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('courier_earnings', 'company_id')) {
            Schema::table('courier_earnings', function (Blueprint $table) {
                $table->dropConstrainedForeignId('company_id');
            });
        }

        if (Schema::hasColumn('deliveries', 'company_id')) {
            Schema::table('deliveries', function (Blueprint $table) {
                $table->dropConstrainedForeignId('company_id');
            });
        }

        if (Schema::hasColumn('couriers', 'company_id')) {
            Schema::table('couriers', function (Blueprint $table) {
                $table->dropConstrainedForeignId('company_id');
            });
        }

        if (Schema::hasColumn('partners', 'company_id')) {
            Schema::table('partners', function (Blueprint $table) {
                $table->dropConstrainedForeignId('company_id');
            });
        }

        if (Schema::hasColumn('users', 'company_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('company_id');
            });
        }

        Schema::dropIfExists('companies');
    }
};
