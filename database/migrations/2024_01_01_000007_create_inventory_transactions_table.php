<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relief_item_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['in', 'out', 'distribute', 'adjustment', 'expired', 'damaged', 'returned']);
            $table->integer('quantity');
            $table->string('reference_type')->nullable(); // purchase, donation, distribution, transfer
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->foreignId('processed_by')->constrained('users')->nullOnDelete();
            $table->foreignId('evacuation_center_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('disaster_incident_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();

            $table->index(['relief_item_id', 'type']);
            $table->index(['transaction_date']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};