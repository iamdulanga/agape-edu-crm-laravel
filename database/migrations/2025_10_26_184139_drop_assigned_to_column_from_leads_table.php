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
        Schema::table('leads', function (Blueprint $table) {
            // Drop the foreign key constraint first, then the column
            // The assigned_to column is being removed as it's no longer needed
            // for the CRM functionality
            $table->dropForeign(['assigned_to']);
            $table->dropColumn('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Restore the assigned_to column and foreign key if rollback is needed
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
