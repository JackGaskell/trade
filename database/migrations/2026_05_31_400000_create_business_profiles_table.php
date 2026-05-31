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
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('business_type')->default('sole_trader');
            $table->string('trading_name')->nullable();
            $table->text('address')->nullable();
            $table->boolean('vat_registered')->default(false);
            $table->string('vat_number')->nullable();
            $table->text('utr')->nullable();
            $table->unsignedTinyInteger('accounting_year_start_month')->default(4);
            $table->unsignedTinyInteger('accounting_year_start_day')->default(6);
            $table->boolean('cis_registered')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
