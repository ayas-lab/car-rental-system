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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();       // e.g., RES-20260215-001
            
            // Foreign keys
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');                 // Client
            
            $table->foreignId('vehicle_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Dates
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            
            // Pricing snapshot (IMPORTANT: store rates at time of booking)
            $table->decimal('daily_rate', 8, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            
            // Locations
            $table->string('pickup_location')->nullable();
            $table->string('dropoff_location')->nullable();
            
            // Status
            $table->enum('status', [
                'pending',      // Just created, awaiting confirmation
                'confirmed',    // Confirmed, ready for pickup
                'active',       // Currently rented (contract signed)
                'completed',    // Returned and closed
                'cancelled'     // Cancelled by client or admin
            ])->default('pending');
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
