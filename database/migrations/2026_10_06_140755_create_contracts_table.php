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
        Schema::create('contracts', function (Blueprint $table) {
             $table->id();
            $table->string('contract_number')->unique(); // e.g., CTR-20260215-001
            
            // Foreign keys
            $table->foreignId('reservation_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            $table->foreignId('client_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            $table->foreignId('agent_id')                // Agent who handled it
                  ->constrained('users')
                  ->onDelete('restrict');
            
            // Check-in data (pickup)
            $table->timestamp('signed_at');              // When client signed
            $table->integer('pickup_mileage');
            $table->enum('pickup_fuel_level', ['empty', 'quarter', 'half', 'three_quarters', 'full']);
            $table->text('pickup_condition')->nullable(); // Any damage noted
            
            // Check-out data (return) - nullable until returned
            $table->timestamp('returned_at')->nullable();
            $table->integer('return_mileage')->nullable();
            $table->enum('return_fuel_level', ['empty', 'quarter', 'half', 'three_quarters', 'full'])->nullable();
            $table->text('return_condition')->nullable();
            
            // Extra charges (late fees, fuel, damage)
            $table->decimal('extra_charges', 10, 2)->default(0);
            $table->text('extra_charges_notes')->nullable();
            
            $table->enum('status', ['active', 'completed', 'overdue'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
