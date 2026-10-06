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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('brand');                     // Toyota, BMW, etc.
            $table->string('model');                     // Corolla, X5, etc.
            $table->year('year');
            $table->string('color')->nullable();
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid'])->default('petrol');
            $table->enum('transmission', ['manual', 'automatic'])->default('manual');
            $table->integer('seats')->default(5);
            $table->integer('doors')->default(4);
            $table->decimal('daily_rate', 8, 2);         // Price per day
            $table->integer('mileage')->default(0);      // Current km
            $table->enum('status', ['available', 'rented', 'maintenance', 'out_of_service'])->default('available');
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();                       // Don't truly delete vehicles
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
