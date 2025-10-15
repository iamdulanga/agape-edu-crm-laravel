<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('age')->nullable();
            $table->string('city')->nullable();
            $table->enum('passport', ['yes', 'no'])->nullable();
            $table->date('inquiry_date')->nullable();
            $table->enum('study_level', ['foundation', 'diploma', 'bachelor', 'master', 'phd'])->nullable();
            $table->enum('priority', ['very_high', 'high', 'medium', 'low', 'very_low'])->default('medium');
            $table->text('preferred_universities')->nullable();
            $table->text('special_notes')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'rejected'])->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};