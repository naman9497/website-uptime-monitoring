<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->unique()->constrained()->cascadeOnDelete();

            // Current state
            $table->enum('current_status', ['up', 'down', 'unknown'])->default('unknown')->index();
            $table->timestamp('last_checked_at')->nullable();

            // Alert management
            $table->boolean('alert_sent')->default(false)->index();
            $table->timestamp('alert_sent_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_status');
    }
};
