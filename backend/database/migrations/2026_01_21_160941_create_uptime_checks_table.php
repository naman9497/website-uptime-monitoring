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
        Schema::create('uptime_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();

            // Check results
            $table->enum('status', ['up', 'down'])->index();
            $table->integer('response_time_ms')->nullable();
            $table->integer('http_status_code')->nullable();

            // Error tracking
            $table->string('error_type', 50)->nullable();
            $table->text('error_message')->nullable();

            // Metadata
            $table->timestamp('checked_at')->index();
            $table->timestamps();

            // Indexes for performance
            $table->index(['website_id', 'checked_at']);
            $table->index(['website_id', 'status', 'checked_at']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uptime_checks');
    }
};
