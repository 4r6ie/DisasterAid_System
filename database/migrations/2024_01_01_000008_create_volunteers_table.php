<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->json('skills')->nullable(); // ['first_aid', 'rescue', 'logistics', 'medical', 'communication', 'cooking', 'driving']
            $table->json('availability')->nullable(); // {'monday': ['morning', 'afternoon'], 'tuesday': []}
            $table->enum('status', ['available', 'busy', 'inactive', 'on_leave'])->default('available');
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};