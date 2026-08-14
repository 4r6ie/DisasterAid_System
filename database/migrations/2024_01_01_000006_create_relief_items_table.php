<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('relief_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // food, water, medicine, shelter, clothing, hygiene, tools, other
            $table->string('unit')->default('units'); // units, kg, liters, packs, boxes
            $table->integer('quantity')->default(0);
            $table->integer('min_stock')->default(10);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->string('location')->nullable(); // warehouse location
            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'status']);
            $table->index(['quantity', 'min_stock']);
            $table->index(['expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relief_items');
    }
};