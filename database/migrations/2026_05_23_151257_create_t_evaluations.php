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
        Schema::create('t_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('t_interns')->cascadeOnDelete();
            $table->foreignId('supervisor_id')->constrained('t_supervisors');
            $table->text('overall_feedback')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_evaluations');
    }
};
