<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ui_translations', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50);
            $table->string('key', 100);
            $table->enum('lang', ['uz', 'ru']);
            $table->text('value');
            $table->timestamps();

            $table->unique(['group', 'key', 'lang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ui_translations');
    }
};
