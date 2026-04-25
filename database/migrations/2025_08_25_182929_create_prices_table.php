<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_product_id')->constrained();
            $table->enum('type', ['retail', 'wholesale']);
            $table->unsignedInteger('value');
            $table->enum('currency', ['uzs', 'usd'])->default('uzs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
