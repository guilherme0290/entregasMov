<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courier_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->cascadeOnDelete();
            $table->foreignId('courier_id')->constrained()->cascadeOnDelete();
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('fee_amount', 10, 2)->nullable();
            $table->decimal('net_amount', 10, 2);
            $table->string('payment_status', 30)->default('pending')->index();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courier_earnings');
    }
};
