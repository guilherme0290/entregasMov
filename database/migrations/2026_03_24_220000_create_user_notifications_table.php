<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 80);
            $table->string('title');
            $table->text('message');
            $table->json('payload')->nullable();
            $table->timestamp('read_at')->nullable()->index();
            $table->timestamps();

            $table->index(['company_id', 'user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
