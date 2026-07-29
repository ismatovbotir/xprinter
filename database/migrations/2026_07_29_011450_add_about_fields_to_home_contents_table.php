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
        Schema::table('home_contents', function (Blueprint $table) {
            $table->string('about_tag')->nullable();
            $table->string('about_title')->nullable();
            $table->string('about_subtitle')->nullable();
            $table->json('about_cards')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_contents', function (Blueprint $table) {
            $table->dropColumn(['about_tag', 'about_title', 'about_subtitle', 'about_cards']);
        });
    }
};
