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
        Schema::table('t_onboarding_checklists', function (Blueprint $table) {
            $table->foreignId('checklist_template_id')->nullable()->after('intern_id')
                ->constrained('t_checklist_templates')->nullOnDelete();
            $table->boolean('is_required')->default(true)->after('item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_onboarding_checklists', function (Blueprint $table) {
            $table->dropForeign(['checklist_template_id']);
            $table->dropColumn(['checklist_template_id', 'is_required']);
        });
    }
};
