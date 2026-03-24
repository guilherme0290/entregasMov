<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tax_id', 20)->unique();
            $table->date('birth_date')->nullable();
            $table->string('address');
            $table->string('number', 20)->nullable();
            $table->string('district')->nullable();
            $table->string('city');
            $table->string('state', 2);
            $table->string('zip_code', 10)->nullable();
            $table->string('complement')->nullable();
            $table->text('notes')->nullable();
            $table->string('vehicle_type', 30)->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_plate', 10)->nullable();
            $table->string('document_photo')->nullable();
            $table->string('driver_license_photo')->nullable();
            $table->string('availability_status', 30)->default('offline')->index();
            $table->decimal('current_latitude', 10, 7)->nullable();
            $table->decimal('current_longitude', 10, 7)->nullable();
            $table->timestamp('last_status_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};
