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
        Schema::create('t_competency_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // e.g. Communication, Technical Skills
            $table->text('description')->nullable();
            $table->integer('max_score')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_competency_criteria');
    }
};
