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
                'dashboard'          => ['uz' => 'Bosh sahifa',           'ru' => 'Дашборд',              'en' => 'Dashboard'],
                'countries'          => ['uz' => 'Davlatlar',             'ru' => 'Страны',               'en' => 'Countries'],
                'regions'            => ['uz' => 'Viloyatlar',            'ru' => 'Области',              'en' => 'Regions'],
                'cities'             => ['uz' => 'Shaharlar',             'ru' => 'Города',               'en' => 'Cities'],
                'categories'         => ['uz' => 'Kategoriyalar',         'ru' => 'Категории',            'en' => 'Categories'],
                'parameters'         => ['uz' => 'Parametrlar',           'ru' => 'Параметры',            'en' => 'Parameters'],
                'products'           => ['uz' => 'Mahsulotlar',           'ru' => 'Товары',               'en' => 'Products'],
                'companies'          => ['uz' => 'Kompaniyalar',          'ru' => 'Компании',             'en' => 'Companies'],
                'companies_pending'  => ['uz' => 'Kutilayotganlar',       'ru' => 'Ожидающие',            'en' => 'Pending'],
                'users'              => ['uz' => 'Foydalanuvchilar',      'ru' => 'Пользователи',         'en' => 'Users'],
                'translations'       => ['uz' => 'Tarjimalar',            'ru' => 'Переводы',             'en' => 'Translations'],
            ],
            'common' => [
                'add'                => ['uz' => "Qo'shish",              'ru' => 'Добавить',             'en' => 'Add'],
                'save'               => ['uz' => 'Saqlash',               'ru' => 'Сохранить',            'en' => 'Save'],
                'cancel'             => ['uz' => 'Bekor qilish',          'ru' => 'Отмена',               'en' => 'Cancel'],
                'delete'             => ['uz' => "O'chirish",             'ru' => 'Удалить',              'en' => 'Delete'],
                'edit'               => ['uz' => 'Tahrirlash',            'ru' => 'Редактировать',        'en' => 'Edit'],
                'search'             => ['uz' => 'Qidirish',              'ru' => 'Поиск',                'en' => 'Search'],
                'all'                => ['uz' => 'Barchasi',              'ru' => 'Все',                  'en' => 'All'],
                'back'               => ['uz' => 'Orqaga',                'ru' => 'Назад',                'en' => 'Back'],
                'confirm_delete'     => ['uz' => "O'chirishni tasdiqlaysizmi?", 'ru' => 'Подтвердить удаление?', 'en' => 'Confirm delete?'],
                'no_results'         => ['uz' => 'Natija topilmadi',      'ru' => 'Нет результатов',      'en' => 'No results'],
                'loading'            => ['uz' => 'Yuklanmoqda...',        'ru' => 'Загрузка...',          'en' => 'Loading...'],
                'required_fields'    => ['uz' => "Majburiy maydonlar",    'ru' => 'Обязательные поля',    'en' => 'Required fields'],
                'name_uz'            => ['uz' => "Nomi (O'zbekcha)",      'ru' => 'Название (узбекский)', 'en' => 'Name (Uzbek)'],
                'name_ru'            => ['uz' => 'Nomi (Ruscha)',         'ru' => 'Название (русский)',   'en' => 'Name (Russian)'],
                'name_en'            => ['uz' => 'Nomi (Inglizcha)',      'ru' => 'Название (английский)','en' => 'Name (English)'],
            ],
            'status' => [
                'approved'           => ['uz' => 'Tasdiqlangan',          'ru' => 'Подтверждён',          'en' => 'Approved'],
                'pending'            => ['uz' => 'Kutilmoqda',            'ru' => 'Ожидает',              'en' => 'Pending'],
                'rejected'           => ['uz' => 'Rad etilgan',           'ru' => 'Отклонён',             'en' => 'Rejected'],
                'approve'            => ['uz' => 'Tasdiqlash',            'ru' => 'Подтвердить',          'en' => 'Approve'],
                'reject'             => ['uz' => 'Rad etish',             'ru' => 'Отклонить',            'en' => 'Reject'],
            ],
            'company' => [
                'retail'             => ['uz' => 'Chakana',               'ru' => 'Розница',              'en' => 'Retail'],
                'partner'            => ['uz' => 'Hamkorlik',             'ru' => 'Партнёрство',          'en' => 'Partnership'],
                'service'            => ['uz' => 'Servis',                'ru' => 'Сервис',               'en' => 'Service'],
                'vat_payer'          => ['uz' => "NDS to'lovchi",         'ru' => 'Плательщик НДС',       'en' => 'VAT payer'],
                'vat_non_payer'      => ['uz' => "NDS to'lovchi emas",    'ru' => 'Без НДС',              'en' => 'Non-VAT payer'],
                'authorized_partner' => ['uz' => 'Vakolatli hamkor',      'ru' => 'Авторизованный партнёр','en' => 'Authorized partner'],
                'authorized_dist'    => ['uz' => 'Vakolatli distribyutor','ru' => 'Авторизованный дистрибьютор','en' => 'Authorized distributor'],
                'none'               => ['uz' => 'Oddiy diler',           'ru' => 'Обычный дилер',        'en' => 'Regular dealer'],
            ],
            'notifications' => [
                'company_submitted'  => ['uz' => 'Kompaniya tasdiq kutmoqda', 'ru' => 'Компания ожидает проверки',  'en' => 'Company awaiting approval'],
                'company_approved'   => ['uz' => 'Kompaniyangiz tasdiqlandi', 'ru' => 'Ваша компания подтверждена', 'en' => 'Your company has been approved'],
                'company_rejected'   => ['uz' => 'Kompaniyangiz rad etildi',  'ru' => 'Ваша компания отклонена',    'en' => 'Your company has been rejected'],
                'vat_review_request' => ['uz' => "NDS statusini ko'rib chiqish so'raldi", 'ru' => 'Запрос на проверку НДС статуса', 'en' => 'VAT status review requested'],
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
