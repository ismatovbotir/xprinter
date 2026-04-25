<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('lang', ['uz', 'ru']);
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['product_id', 'lang']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_translations');
    }
};
