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
        Schema::create('t_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('t_users')->cascadeOnDelete();
            $table->string('type');              // task_assigned, task_submitted, logbook_entry, etc.
            $table->string('title');
            $table->text('body')->nullable();
            $table->json('data')->nullable();    // { "task_id": 12 } — related model reference
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_notifications');
    }
};
