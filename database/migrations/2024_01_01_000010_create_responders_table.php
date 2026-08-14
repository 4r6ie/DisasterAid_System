<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('responders', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 50)->unique()->index();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('specialization'); // medical, fire, rescue, police, logistics, coordination, communications
            $table->string('rank')->nullable(); // chief, captain, lieutenant, sergeant, officer, volunteer
            $table->enum('status', ['available', 'on_duty', 'off_duty', 'on_leave', 'inactive'])->default('available');
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->json('certifications')->nullable(); // ['EMT', 'Firefighter I', 'Swift Water Rescue', 'ICS-100']
            $table->json('equipment')->nullable(); // assigned equipment
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('hired_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'specialization']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responders');
    }
};