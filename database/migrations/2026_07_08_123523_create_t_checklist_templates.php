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
        Schema::create('t_checklist_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dept_id')->nullable()->constrained('t_departments')->nullOnDelete();
            $table->string('item_text');
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_checklist_templates');
    }
};
