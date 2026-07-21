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
        Schema::create('t_interns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('t_users')->cascadeOnDelete();
            $table->foreignId('dept_id')->constrained('t_departments')->restrictOnDelete();
            $table->foreignId('supervisor_id')->constrained('t_users')->restrictOnDelete();
            $table->string('institution');      // their university
            $table->string('programme');        // their degree programme
            $table->string('student_number')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_interns');
    }
};
