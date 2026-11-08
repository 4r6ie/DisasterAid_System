<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affected_persons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disaster_incident_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('condition', ['safe', 'injured', 'critical', 'missing', 'deceased', 'rescued'])->default('safe');
            $table->text('medical_notes')->nullable();
            $table->string('family_contact_name')->nullable();
            $table->string('family_contact_phone')->nullable();
            $table->foreignId('evacuation_center_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('evacuated_at')->nullable();
            $table->foreignId('registered_by')->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['disaster_incident_id', 'condition']);
            $table->index(['evacuation_center_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affected_persons');
    }
};