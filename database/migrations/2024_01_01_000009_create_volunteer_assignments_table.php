<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('volunteer_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('disaster_incident_id')->constrained()->cascadeOnDelete();
            $table->string('role'); // team_leader, medical, logistics, rescue, communication, general
            $table->enum('status', ['assigned', 'active', 'completed', 'cancelled'])->default('assigned');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assigned_by')->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['volunteer_id', 'status']);
            $table->index(['disaster_incident_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_assignments');
    }
};