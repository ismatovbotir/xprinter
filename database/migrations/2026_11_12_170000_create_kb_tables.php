<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Knowledge base: how to fix, how to configure, what a driver / tool is.
 * Public at /support, edited in /admin/kb, read by the chatbot.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kb_articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->enum('type', ['troubleshooting', 'settings', 'driver', 'tool', 'glossary', 'faq'])->index();
            // Article applies to a whole category (nullable) and/or specific models (pivot).
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('difficulty', ['easy', 'medium', 'pro'])->default('easy');
            // Symptoms / synonyms in any language — feeds search and the chatbot.
            $table->text('keywords')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });

        Schema::create('kb_article_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kb_article_id')->constrained()->cascadeOnDelete();
            $table->enum('lang', ['uz', 'ru', 'en']);
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->text('body'); // simple HTML: p, ol, ul, li, strong, code, a
            $table->timestamps();
            $table->unique(['kb_article_id', 'lang']);
        });

        Schema::create('kb_article_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kb_article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unique(['kb_article_id', 'product_id']);
        });

        Schema::create('kb_article_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kb_article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('file_id')->constrained()->cascadeOnDelete();
            $table->unique(['kb_article_id', 'file_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kb_article_file');
        Schema::dropIfExists('kb_article_product');
        Schema::dropIfExists('kb_article_translations');
        Schema::dropIfExists('kb_articles');
    }
};
