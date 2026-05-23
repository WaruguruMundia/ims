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
        Schema::create('t_evaluation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('t_evaluations')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('t_competency_criteria');
            $table->integer('score');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['evaluation_id', 'criteria_id']); // one score per criterion per evaluation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_evaluation_scores');
    }
};
