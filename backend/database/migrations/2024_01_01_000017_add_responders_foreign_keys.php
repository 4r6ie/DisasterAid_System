<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * disaster_incidents and emergency_reports reference the responders table,
 * which is created later in the migration order. The foreign keys are added
 * here, once both tables exist.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('disaster_incidents', function (Blueprint $table) {
            $table->foreign('assigned_responder_id')
                ->references('id')
                ->on('responders')
                ->nullOnDelete();
        });

        Schema::table('emergency_reports', function (Blueprint $table) {
            $table->foreign('assigned_responder_id')
                ->references('id')
                ->on('responders')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('emergency_reports', function (Blueprint $table) {
            $table->dropForeign(['assigned_responder_id']);
        });

        Schema::table('disaster_incidents', function (Blueprint $table) {
            $table->dropForeign(['assigned_responder_id']);
        });
    }
};
