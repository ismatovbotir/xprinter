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
        Schema::create('help_articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->enum('section', ['marketplace.dashboard', 'marketplace.assortiment', 'marketplace.team', 'marketplace.company', 'admin.products', 'admin.companies', 'admin.files', 'admin.banners'])->nullable();
            $table->enum('placement', ['icon', 'tooltip', 'modal', 'sidebar'])->default('icon');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('help_article_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('help_article_id')->constrained()->cascadeOnDelete();
            $table->enum('lang', ['uz', 'ru', 'en']);
            $table->string('title');
            $table->text('content');
            $table->unique(['help_article_id', 'lang']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_articles');
    }
};
