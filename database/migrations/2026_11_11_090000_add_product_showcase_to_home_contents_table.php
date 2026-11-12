<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_contents', function (Blueprint $table) {
            $table->json('hero_products')->nullable();     // [product_id, …] — hero carousel, in order
            $table->json('scenario_products')->nullable(); // {"shop": [ids], "cafe": [ids], …}
        });
    }

    public function down(): void
    {
        Schema::table('home_contents', function (Blueprint $table) {
            $table->dropColumn(['hero_products', 'scenario_products']);
        });
    }
};
