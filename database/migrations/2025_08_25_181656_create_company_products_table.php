<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_products', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('company_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('quantity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_products');
    }
};
