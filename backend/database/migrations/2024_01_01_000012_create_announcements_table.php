<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['alert', 'advisory', 'notice', 'update', 'evacuation', 'weather'])->default('notice');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('audience', ['public', 'responders', 'staff', 'volunteers', 'affected'])->default('public');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('disaster_incident_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'published_at']);
            $table->index(['type', 'priority']);
            $table->index(['disaster_incident_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};