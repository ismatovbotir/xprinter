<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@xprinter.uz')],
            [
                'name'     => env('ADMIN_NAME', 'Admin'),
                'role'     => 'admin',
                'lang'     => 'uz',
                'password' => env('ADMIN_PASSWORD', 'changeme'),
            ]
        );

        $this->call(UiTranslationSeeder::class);
        $this->call(UzbekistanSeeder::class);
        $this->call(CatalogSeeder::class);
    }
}
