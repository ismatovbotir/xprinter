<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('inn')->nullable();
            $table->string('phone')->nullable();
            $table->string('legal_form')->nullable();
            $table->string('logo')->nullable();
            $table->string('slug')->unique();
            $table->json('types')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'blocked'])->default('pending');
            $table->enum('vat_status', ['non_payer', 'payer'])->default('non_payer');
            $table->enum('manufacturer_status', ['none', 'authorized_partner', 'authorized_distributor'])->default('none');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
