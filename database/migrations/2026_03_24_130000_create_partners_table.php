<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('trade_name');
            $table->string('company_name')->nullable();
            $table->string('tax_id', 20)->unique();
            $table->string('contact_name');
            $table->string('contact_phone', 20);
            $table->string('billing_email')->nullable();
            $table->string('pickup_address');
            $table->string('pickup_number', 20)->nullable();
            $table->string('pickup_district')->nullable();
            $table->string('pickup_city');
            $table->string('pickup_state', 2);
            $table->string('pickup_zip_code', 10)->nullable();
            $table->string('pickup_complement')->nullable();
            $table->decimal('default_delivery_fee', 10, 2)->default(0);
            $table->decimal('urgent_delivery_fee', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
