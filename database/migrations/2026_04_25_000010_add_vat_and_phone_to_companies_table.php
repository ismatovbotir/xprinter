<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('inn');
            $table->enum('vat_status', ['non_payer', 'payer'])
                  ->default('non_payer')
                  ->after('status')
                  ->comment('Set by admin only. Affects price display (with/without VAT).');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['phone', 'vat_status']);
        });
    }
};
