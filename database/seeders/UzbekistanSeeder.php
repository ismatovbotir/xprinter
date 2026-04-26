<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Seeder;

class UzbekistanSeeder extends Seeder
{
    public function run(): void
    {
        $country = Country::firstOrCreate(
            ['code' => 'UZ'],
        );

        foreach (['uz', 'ru', 'en'] as $lang) {
            $country->translations()->updateOrCreate(
                ['lang' => $lang],
                ['name' => match($lang) {
                    'uz' => "O'zbekiston",
                    'ru' => 'Узбекистан',
                    'en' => 'Uzbekistan',
                }]
            );
        }

        $regions = [
            ['uz' => 'Andijon viloyati',              'ru' => 'Андижанская область',          'en' => 'Andijan region'],
            ['uz' => 'Buxoro viloyati',               'ru' => 'Бухарская область',            'en' => 'Bukhara region'],
            ['uz' => 'Jizzax viloyati',               'ru' => 'Джизакская область',           'en' => 'Jizzakh region'],
            ['uz' => 'Qashqadaryo viloyati',          'ru' => 'Кашкадарьинская область',      'en' => 'Kashkadarya region'],
            ['uz' => 'Navoiy viloyati',               'ru' => 'Навоийская область',           'en' => 'Navoiy region'],
            ['uz' => 'Namangan viloyati',              'ru' => 'Наманганская область',         'en' => 'Namangan region'],
            ['uz' => 'Samarqand viloyati',            'ru' => 'Самаркандская область',        'en' => 'Samarkand region'],
            ['uz' => 'Sirdaryo viloyati',             'ru' => 'Сырдарьинская область',        'en' => 'Sirdarya region'],
            ['uz' => 'Surxondaryo viloyati',          'ru' => 'Сурхандарьинская область',     'en' => 'Surkhandarya region'],
            ['uz' => 'Toshkent viloyati',             'ru' => 'Ташкентская область',          'en' => 'Tashkent region'],
            ["uz" => "Farg'ona viloyati",             'ru' => 'Ферганская область',           'en' => 'Fergana region'],
            ['uz' => 'Xorazm viloyati',               'ru' => 'Хорезмская область',           'en' => 'Khorezm region'],
            ["uz" => "Qoraqalpog'iston Respublikasi", 'ru' => 'Республика Каракалпакстан',    'en' => 'Republic of Karakalpakstan'],
            ['uz' => 'Toshkent shahri',               'ru' => 'город Ташкент',                'en' => 'Tashkent city'],
        ];

        foreach ($regions as $names) {
            $existing = Region::where('country_id', $country->id)
                ->whereHas('translations', fn($q) =>
                    $q->where('lang', 'uz')->where('name', $names['uz'])
                )->first();

            $region = $existing ?? Region::create(['country_id' => $country->id]);

            foreach (['uz', 'ru', 'en'] as $lang) {
                $region->translations()->updateOrCreate(
                    ['lang' => $lang],
                    ['name' => $names[$lang]]
                );
            }
        }
    }
}
