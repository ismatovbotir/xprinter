<?php

namespace Database\Seeders;

use App\Models\UiTranslation;
use Illuminate\Database\Seeder;

class UiTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            'menu' => [
                'dashboard'          => ['uz' => 'Bosh sahifa',           'ru' => 'Дашборд'],
                'countries'          => ['uz' => 'Davlatlar',             'ru' => 'Страны'],
                'regions'            => ['uz' => 'Viloyatlar',            'ru' => 'Области'],
                'cities'             => ['uz' => 'Shaharlar',             'ru' => 'Города'],
                'categories'         => ['uz' => 'Kategoriyalar',         'ru' => 'Категории'],
                'parameters'         => ['uz' => 'Parametrlar',           'ru' => 'Параметры'],
                'products'           => ['uz' => 'Mahsulotlar',           'ru' => 'Товары'],
                'companies'          => ['uz' => 'Kompaniyalar',          'ru' => 'Компании'],
                'companies_pending'  => ['uz' => 'Kutilayotganlar',       'ru' => 'Ожидающие'],
                'users'              => ['uz' => 'Foydalanuvchilar',      'ru' => 'Пользователи'],
                'translations'       => ['uz' => 'Tarjimalar',            'ru' => 'Переводы'],
            ],
            'common' => [
                'add'                => ['uz' => "Qo'shish",              'ru' => 'Добавить'],
                'save'               => ['uz' => 'Saqlash',               'ru' => 'Сохранить'],
                'cancel'             => ['uz' => 'Bekor qilish',          'ru' => 'Отмена'],
                'delete'             => ['uz' => "O'chirish",             'ru' => 'Удалить'],
                'edit'               => ['uz' => 'Tahrirlash',            'ru' => 'Редактировать'],
                'search'             => ['uz' => 'Qidirish',              'ru' => 'Поиск'],
                'all'                => ['uz' => 'Barchasi',              'ru' => 'Все'],
                'back'               => ['uz' => 'Orqaga',                'ru' => 'Назад'],
                'confirm_delete'     => ['uz' => "O'chirishni tasdiqlaysizmi?", 'ru' => 'Подтвердить удаление?'],
                'no_results'         => ['uz' => 'Natija topilmadi',      'ru' => 'Нет результатов'],
                'loading'            => ['uz' => 'Yuklanmoqda...',        'ru' => 'Загрузка...'],
                'required_fields'    => ['uz' => "Majburiy maydonlar",    'ru' => 'Обязательные поля'],
                'name_uz'            => ['uz' => "Nomi (O'zbekcha)",      'ru' => 'Название (узбекский)'],
                'name_ru'            => ['uz' => 'Nomi (Ruscha)',         'ru' => 'Название (русский)'],
            ],
            'status' => [
                'approved'           => ['uz' => 'Tasdiqlangan',          'ru' => 'Подтверждён'],
                'pending'            => ['uz' => 'Kutilmoqda',            'ru' => 'Ожидает'],
                'rejected'           => ['uz' => 'Rad etilgan',           'ru' => 'Отклонён'],
                'approve'            => ['uz' => 'Tasdiqlash',            'ru' => 'Подтвердить'],
                'reject'             => ['uz' => 'Rad etish',             'ru' => 'Отклонить'],
            ],
            'company' => [
                'retail'             => ['uz' => 'Chakana',               'ru' => 'Розница'],
                'partner'            => ['uz' => 'Hamkorlik',             'ru' => 'Партнёрство'],
                'service'            => ['uz' => 'Servis',                'ru' => 'Сервис'],
                'vat_payer'          => ['uz' => "NDS to'lovchi",         'ru' => 'Плательщик НДС'],
                'vat_non_payer'      => ['uz' => "NDS to'lovchi emas",    'ru' => 'Без НДС'],
                'authorized_partner' => ['uz' => 'Vakolatli hamkor',      'ru' => 'Авторизованный партнёр'],
                'authorized_dist'    => ['uz' => 'Vakolatli distribyutor','ru' => 'Авторизованный дистрибьютор'],
                'none'               => ['uz' => 'Oddiy diler',           'ru' => 'Обычный дилер'],
            ],
            'notifications' => [
                'company_submitted'  => ['uz' => 'Kompaniya tasdiq kutmoqda', 'ru' => 'Компания ожидает проверки'],
                'company_approved'   => ['uz' => 'Kompaniyangiz tasdiqlandi', 'ru' => 'Ваша компания подтверждена'],
                'company_rejected'   => ['uz' => 'Kompaniyangiz rad etildi',  'ru' => 'Ваша компания отклонена'],
                'vat_review_request' => ['uz' => "NDS statusini ko'rib chiqish so'raldi", 'ru' => 'Запрос на проверку НДС статуса'],
            ],
        ];

        foreach ($translations as $group => $keys) {
            foreach ($keys as $key => $langs) {
                foreach ($langs as $lang => $value) {
                    UiTranslation::updateOrCreate(
                        ['group' => $group, 'key' => $key, 'lang' => $lang],
                        ['value' => $value]
                    );
                }
            }
        }
    }
}
