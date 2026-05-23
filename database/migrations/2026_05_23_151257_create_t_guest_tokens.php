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
        Schema::create('t_guest_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('t_interns')->cascadeOnDelete();
            $table->foreignId('generated_by')->constrained('t_users');
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->boolean('is_revoked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_guest_tokens');
    }
};
