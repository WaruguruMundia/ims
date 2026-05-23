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
        Schema::create('t_onboarding_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('t_interns')->cascadeOnDelete();
            $table->string('item');               // e.g. "NDA signed", "ID submitted"
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('t_users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_onboarding_checklists');
    }
};
