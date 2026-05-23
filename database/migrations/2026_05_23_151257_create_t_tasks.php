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
        Schema::create('t_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('t_interns')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('t_users'); // supervisor or admin
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'submitted', 'approved', 'rejected'])
                ->default('pending');
            $table->date('due_date');
            $table->text('deliverable_notes')->nullable();   // what the intern must submit
            $table->text('submission_notes')->nullable();    // intern's submission comment
            $table->text('reviewer_feedback')->nullable();  // supervisor's review comment
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_tasks');
    }
};
