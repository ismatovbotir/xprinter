<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameter_value_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parameter_value_id')->constrained()->cascadeOnDelete();
            $table->enum('lang', ['uz', 'ru', 'en']);
            $table->string('name');
            $table->unique(['parameter_value_id', 'lang']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameter_value_translations');
    }
};
