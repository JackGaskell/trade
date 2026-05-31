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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_id')->nullable()->constrained('trade_jobs')->nullOnDelete();
            $table->date('expense_date');
            $table->string('supplier');
            $table->text('description')->nullable();
            $table->string('category');
            $table->decimal('amount', 10, 2);
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('receipt_original_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
