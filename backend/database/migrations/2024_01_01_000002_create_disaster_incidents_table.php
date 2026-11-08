<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disaster_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_id', 50)->unique()->index();
            $table->string('disaster_type'); // earthquake, flood, typhoon, fire, landslide, volcanic, tsunami, other
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('severity', ['low', 'moderate', 'high', 'critical'])->default('low');
            $table->enum('status', ['reported', 'verified', 'active', 'responding', 'contained', 'resolved'])->default('reported');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->integer('affected_count')->default(0);
            $table->integer('injured_count')->default(0);
            $table->integer('missing_count')->default(0);
            $table->integer('deceased_count')->default(0);
            $table->integer('rescued_count')->default(0);
            $table->timestamp('occurred_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('reported_by')->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_responder_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'severity']);
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disaster_incidents');
    }
};