<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();  // e.g., INV-20260215-001
            
            $table->foreignId('reservation_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            $table->foreignId('client_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Amounts
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            $table->enum('status', ['unpaid', 'paid', 'overdue', 'cancelled'])->default('unpaid');
            
            $table->date('issued_at');
            $table->date('due_at');
            $table->timestamp('paid_at')->nullable();
            
            $table->string('pdf_path')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};