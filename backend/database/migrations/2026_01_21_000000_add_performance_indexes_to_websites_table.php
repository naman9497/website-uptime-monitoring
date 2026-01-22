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
        Schema::table('websites', function (Blueprint $table) {
            // Index for duplicate URL detection and faster URL lookups
            $table->index('url');

            // Composite index for client-based queries with sorting by creation date
            $table->index(['client_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropIndex(['url']);
            $table->dropIndex(['client_id', 'created_at']);
        });
    }
};
