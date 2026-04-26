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
            ['email' => 'tiboruz@gmail.com'],
            [
                'name'     => 'Botir',
                'role'     => 'admin',
                'lang'     => 'uz',
                'password' => 'qwerty&123456789',
            ]
        );

        $this->call(UiTranslationSeeder::class);
        $this->call(UzbekistanSeeder::class);
    }
}
