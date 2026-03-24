<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('courier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('request_source', 30)->default('partner_web');
            $table->string('pickup_address');
            $table->string('pickup_number', 20)->nullable();
            $table->string('pickup_district')->nullable();
            $table->string('pickup_city');
            $table->string('pickup_state', 2);
            $table->string('pickup_zip_code', 10)->nullable();
            $table->string('pickup_complement')->nullable();
            $table->string('pickup_reference')->nullable();
            $table->string('dropoff_address');
            $table->string('dropoff_number', 20)->nullable();
            $table->string('dropoff_district')->nullable();
            $table->string('dropoff_city');
            $table->string('dropoff_state', 2);
            $table->string('dropoff_zip_code', 10)->nullable();
            $table->string('dropoff_complement')->nullable();
            $table->string('dropoff_reference')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->decimal('delivery_fee', 10, 2);
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->unsignedInteger('estimated_time_min')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('pickup_started_at')->nullable();
            $table->timestamp('in_transit_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
