<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'ui_translations',
        'country_translations',
        'region_translations',
        'city_translations',
        'category_translations',
        'product_translations',
        'parameter_translations',
        'parameter_value_translations',
    ];

    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            foreach ($this->tables as $table) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `lang` ENUM('uz', 'ru', 'en') NOT NULL");
            }
            return;
        }

        // SQLite: recreate each table replacing the check constraint
        foreach ($this->tables as $table) {
            $this->alterSqliteEnum($table);
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            foreach ($this->tables as $table) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `lang` ENUM('uz', 'ru') NOT NULL");
            }
            return;
        }

        foreach ($this->tables as $table) {
            $this->alterSqliteEnum($table, ['uz', 'ru']);
        }
    }

    private function alterSqliteEnum(string $table, array $values = ['uz', 'ru', 'en']): void
    {
        $rows = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name=?", [$table]);
        if (empty($rows)) {
            return;
        }

        $oldSql = $rows[0]->sql;

        $inList   = implode("', '", $values);
        $newCheck = "check (\"lang\" in ('{$inList}'))";

        // Replace the old check constraint for the lang column
        $newSql = preg_replace(
            '/check \("lang" in \([^)]+\)\)/',
            $newCheck,
            $oldSql
        );

        if ($newSql === $oldSql) {
            return; // constraint already matches or not found
        }

        $tmp = $table . '_tmp_' . time();
        $newSql = str_replace("CREATE TABLE \"{$table}\"", "CREATE TABLE \"{$tmp}\"", $newSql);

        DB::statement($newSql);
        DB::statement("INSERT INTO \"{$tmp}\" SELECT * FROM \"{$table}\"");
        Schema::drop($table);
        DB::statement("ALTER TABLE \"{$tmp}\" RENAME TO \"{$table}\"");
    }
};
