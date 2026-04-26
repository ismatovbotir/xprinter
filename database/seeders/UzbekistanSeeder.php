<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Seeder;

class UzbekistanSeeder extends Seeder
{
    public function run(): void
    {
        $country = Country::firstOrCreate(['code' => 'UZ']);

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

        $data = [
            [
                'uz' => 'Andijon viloyati', 'ru' => 'Андижанская область', 'en' => 'Andijan region',
                'cities' => [
                    ['uz' => 'Andijon', 'ru' => 'Андижан', 'en' => 'Andijan'],
                    ['uz' => 'Asaka', 'ru' => 'Асака', 'en' => 'Asaka'],
                ],
            ],
            [
                'uz' => 'Buxoro viloyati', 'ru' => 'Бухарская область', 'en' => 'Bukhara region',
                'cities' => [
                    ['uz' => 'Buxoro', 'ru' => 'Бухара', 'en' => 'Bukhara'],
                    ['uz' => 'Kogon', 'ru' => 'Каган', 'en' => 'Kogon'],
                ],
            ],
            [
                'uz' => 'Jizzax viloyati', 'ru' => 'Джизакская область', 'en' => 'Jizzakh region',
                'cities' => [
                    ['uz' => 'Jizzax', 'ru' => 'Джизак', 'en' => 'Jizzakh'],
                ],
            ],
            [
                'uz' => 'Qashqadaryo viloyati', 'ru' => 'Кашкадарьинская область', 'en' => 'Kashkadarya region',
                'cities' => [
                    ['uz' => 'Qarshi', 'ru' => 'Карши', 'en' => 'Karshi'],
                    ['uz' => 'Shahrisabz', 'ru' => 'Шахрисабз', 'en' => 'Shahrisabz'],
                ],
            ],
            [
                'uz' => 'Navoiy viloyati', 'ru' => 'Навоийская область', 'en' => 'Navoiy region',
                'cities' => [
                    ['uz' => 'Navoiy', 'ru' => 'Навои', 'en' => 'Navoi'],
                    ['uz' => 'Zarafshon', 'ru' => 'Зарафшан', 'en' => 'Zarafshan'],
                ],
            ],
            [
                'uz' => 'Namangan viloyati', 'ru' => 'Наманганская область', 'en' => 'Namangan region',
                'cities' => [
                    ['uz' => 'Namangan', 'ru' => 'Наманган', 'en' => 'Namangan'],
                    ['uz' => 'Chortoq', 'ru' => 'Чартак', 'en' => 'Chortoq'],
                ],
            ],
            [
                'uz' => 'Samarqand viloyati', 'ru' => 'Самаркандская область', 'en' => 'Samarkand region',
                'cities' => [
                    ['uz' => 'Samarqand', 'ru' => 'Самарканд', 'en' => 'Samarkand'],
                    ['uz' => 'Kattaqo\'rg\'on', 'ru' => 'Каттакурган', 'en' => 'Kattakurgan'],
                ],
            ],
            [
                'uz' => 'Sirdaryo viloyati', 'ru' => 'Сырдарьинская область', 'en' => 'Sirdarya region',
                'cities' => [
                    ['uz' => 'Guliston', 'ru' => 'Гулистан', 'en' => 'Gulistan'],
                    ['uz' => 'Yangiyer', 'ru' => 'Янгиер', 'en' => 'Yangiyer'],
                ],
            ],
            [
                'uz' => 'Surxondaryo viloyati', 'ru' => 'Сурхандарьинская область', 'en' => 'Surkhandarya region',
                'cities' => [
                    ['uz' => 'Termiz', 'ru' => 'Термез', 'en' => 'Termez'],
                    ['uz' => 'Denov', 'ru' => 'Денау', 'en' => 'Denau'],
                ],
            ],
            [
                'uz' => 'Toshkent viloyati', 'ru' => 'Ташкентская область', 'en' => 'Tashkent region',
                'cities' => [
                    ['uz' => 'Nurafshon', 'ru' => 'Нурафшон', 'en' => 'Nurafshon'],
                    ['uz' => 'Chirchiq', 'ru' => 'Чирчик', 'en' => 'Chirchiq'],
                    ['uz' => 'Olmaliq', 'ru' => 'Алмалык', 'en' => 'Olmaliq'],
                    ['uz' => 'Angren', 'ru' => 'Ангрен', 'en' => 'Angren'],
                ],
            ],
            [
                'uz' => "Farg'ona viloyati", 'ru' => 'Ферганская область', 'en' => 'Fergana region',
                'cities' => [
                    ['uz' => "Farg'ona", 'ru' => 'Фергана', 'en' => 'Fergana'],
                    ['uz' => 'Marg\'ilon', 'ru' => 'Маргилан', 'en' => 'Margilan'],
                    ['uz' => 'Qo\'qon', 'ru' => 'Коканд', 'en' => 'Kokand'],
                ],
            ],
            [
                'uz' => 'Xorazm viloyati', 'ru' => 'Хорезмская область', 'en' => 'Khorezm region',
                'cities' => [
                    ['uz' => 'Urganch', 'ru' => 'Ургенч', 'en' => 'Urgench'],
                    ['uz' => 'Xiva', 'ru' => 'Хива', 'en' => 'Khiva'],
                ],
            ],
            [
                'uz' => "Qoraqalpog'iston Respublikasi", 'ru' => 'Республика Каракалпакстан', 'en' => 'Republic of Karakalpakstan',
                'cities' => [
                    ['uz' => 'Nukus', 'ru' => 'Нукус', 'en' => 'Nukus'],
                    ['uz' => 'Xo\'jayli', 'ru' => 'Ходжейли', 'en' => 'Xojayli'],
                ],
            ],
            [
                'uz' => 'Toshkent shahri', 'ru' => 'город Ташкент', 'en' => 'Tashkent city',
                'cities' => [
                    ['uz' => 'Yunusobod tumani', 'ru' => 'Юнусабадский район', 'en' => 'Yunusabad district'],
                    ['uz' => 'Chilonzor tumani', 'ru' => 'Чиланзарский район', 'en' => 'Chilanzar district'],
                    ['uz' => 'Mirzo Ulug\'bek tumani', 'ru' => 'район Мирзо-Улугбека', 'en' => 'Mirzo Ulugbek district'],
                    ['uz' => 'Shayxontohur tumani', 'ru' => 'Шайхантахурский район', 'en' => 'Shaykhantakhur district'],
                    ['uz' => 'Yakkasaroy tumani', 'ru' => 'Яккасарайский район', 'en' => 'Yakkasaray district'],
                    ['uz' => 'Bektemir tumani', 'ru' => 'Бектемирский район', 'en' => 'Bektemir district'],
                    ['uz' => 'Sergeli tumani', 'ru' => 'Сергелийский район', 'en' => 'Sergeli district'],
                    ['uz' => 'Olmazor tumani', 'ru' => 'Алмазарский район', 'en' => 'Almazar district'],
                    ['uz' => 'Mirobod tumani', 'ru' => 'Мирабадский район', 'en' => 'Mirobod district'],
                    ['uz' => 'Uchtepa tumani', 'ru' => 'Учтепинский район', 'en' => 'Uchtepa district'],
                    ['uz' => 'Yashnobod tumani', 'ru' => 'Яшнободский район', 'en' => 'Yashnobod district'],
                ],
            ],
        ];

        foreach ($data as $item) {
            $region = Region::where('country_id', $country->id)
                ->whereHas('translations', fn($q) =>
                    $q->where('lang', 'uz')->where('name', $item['uz'])
                )->first();

            if (!$region) {
                $region = Region::create(['country_id' => $country->id]);
            }

            foreach (['uz', 'ru', 'en'] as $lang) {
                $region->translations()->updateOrCreate(
                    ['lang' => $lang],
                    ['name' => $item[$lang]]
                );
            }

            foreach ($item['cities'] as $cityNames) {
                $city = City::where('region_id', $region->id)
                    ->whereHas('translations', fn($q) =>
                        $q->where('lang', 'uz')->where('name', $cityNames['uz'])
                    )->first();

                if (!$city) {
                    $city = City::create(['region_id' => $region->id]);
                }

                foreach (['uz', 'ru', 'en'] as $lang) {
                    $city->translations()->updateOrCreate(
                        ['lang' => $lang],
                        ['name' => $cityNames[$lang]]
                    );
                }
            }
        }
    }
}
