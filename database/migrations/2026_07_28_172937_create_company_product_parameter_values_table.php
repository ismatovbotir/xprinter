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
        Schema::create('company_product_parameter_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parameter_id')->constrained();
            $table->foreignId('parameter_value_id')->constrained();
            $table->timestamps();

            $table->unique(['company_product_id', 'parameter_id'], 'cppv_company_product_parameter_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_product_parameter_values');
    }
};
