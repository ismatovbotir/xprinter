<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Q&A the admin "teaches" the bot. Used as the highest-priority context.
        Schema::create('chatbot_knowledge', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('answer');
            $table->enum('lang', ['uz', 'ru'])->nullable(); // null = any language
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Questions the bot could not answer — admin answers them and teaches the bot.
        Schema::create('chatbot_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('channel', ['web', 'telegram']);
            $table->enum('lang', ['uz', 'ru'])->default('uz');
            $table->text('question');
            $table->text('answer')->nullable();
            $table->enum('status', ['open', 'answered'])->default('open');
            $table->timestamps();
        });

        Schema::table('client_messages', function (Blueprint $table) {
            $table->enum('sender', ['client', 'admin', 'bot'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('client_messages', function (Blueprint $table) {
            $table->enum('sender', ['client', 'admin'])->change();
        });

        Schema::dropIfExists('chatbot_questions');
        Schema::dropIfExists('chatbot_knowledge');
    }
};
