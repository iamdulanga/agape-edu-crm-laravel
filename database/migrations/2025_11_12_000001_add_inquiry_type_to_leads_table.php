<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds a nullable 'inquiry_type' string column to the 'leads' table.
     * This column is used to store the type of inquiry for each lead.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('inquiry_type')->nullable()->after('avatar')->comment('Type of inquiry for the lead');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the 'inquiry_type' column from the 'leads' table.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('inquiry_type');
        });
    }
};
