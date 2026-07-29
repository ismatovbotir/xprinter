<?php

namespace Database\Seeders;

use App\Models\HelpArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds a handful of help articles across every section and every placement type
 * (icon / tooltip / modal / sidebar) so the help system has real content to click
 * through instead of an empty admin.help list.
 */
class HelpContentSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'section' => 'marketplace.dashboard', 'placement' => 'icon', 'sort_order' => 1,
                'uz' => ['title' => 'Dashboard nimani ko\'rsatadi', 'content' => 'Bosh sahifada kompaniyangizning holati, assortimentdagi mahsulotlar soni va oxirgi faoliyat ko\'rinadi. Kompaniyangiz hali tasdiqlanmagan bo\'lsa, bu yerda tekshiruv holati chiqadi.'],
                'ru' => ['title' => 'Что показывает Dashboard', 'content' => 'На главной странице отображается статус вашей компании, количество товаров в ассортименте и последняя активность. Если компания ещё не одобрена, здесь будет виден статус проверки.'],
                'en' => ['title' => 'What the Dashboard shows', 'content' => 'The home page shows your company\'s status, the number of products in your assortment, and recent activity. If your company isn\'t approved yet, the review status appears here.'],
            ],
            [
                'section' => 'marketplace.assortiment', 'placement' => 'tooltip', 'sort_order' => 1,
                'uz' => ['title' => 'Mahsulot qanday qo\'shiladi', 'content' => 'Katalogdan mahsulotni tanlang, chakana va ulgurji narxlarni kiriting. Agar mahsulot rangi yoki interfeysi kabi parametrlar bo\'lsa, ularni ham belgilashingiz kerak bo\'ladi.'],
                'ru' => ['title' => 'Как добавить товар', 'content' => 'Выберите товар из каталога, укажите розничную и оптовую цену. Если у товара есть варианты — например, цвет или интерфейс — их тоже нужно будет указать.'],
                'en' => ['title' => 'How to add a product', 'content' => 'Pick a product from the catalog and set the retail and wholesale price. If the product has variant options — like color or interface — you\'ll need to select those too.'],
            ],
            [
                'section' => 'marketplace.team', 'placement' => 'icon', 'sort_order' => 1,
                'uz' => ['title' => 'Xodim qo\'shish', 'content' => 'Jamoa bo\'limidan kompaniyangizga menejer qo\'shishingiz mumkin. Qo\'shilgan xodim sizning kompaniyangiz nomidan assortiment va narxlarni boshqara oladi.'],
                'ru' => ['title' => 'Добавление сотрудника', 'content' => 'В разделе «Команда» вы можете добавить менеджера в свою компанию. Добавленный сотрудник сможет управлять ассортиментом и ценами от имени компании.'],
                'en' => ['title' => 'Adding a team member', 'content' => 'From the Team section you can add a manager to your company. Added staff can manage the assortment and prices on your company\'s behalf.'],
            ],
            [
                'section' => 'marketplace.company', 'placement' => 'modal', 'sort_order' => 1,
                'uz' => ['title' => 'NDS holati nima uchun kerak', 'content' => 'NDS (QQS) holatingizga qarab, mahsulot narxlari QQS bilan yoki QQSsiz ko\'rsatiladi. Holatni faqat admin o\'zgartira oladi — siz "Tekshirishni so\'rash" tugmasi orqali murojaat qilishingiz mumkin.'],
                'ru' => ['title' => 'Зачем нужен статус НДС', 'content' => 'В зависимости от статуса плательщика НДС цены товаров показываются с НДС или без. Статус может изменить только администратор — вы можете отправить запрос через кнопку «Запросить проверку».'],
                'en' => ['title' => 'Why the VAT status matters', 'content' => 'Depending on your VAT payer status, product prices are shown with or without VAT. Only an admin can change this status — you can request a review via the "Request review" button.'],
            ],
            [
                'section' => 'admin.products', 'placement' => 'sidebar', 'sort_order' => 1,
                'uz' => ['title' => 'Mahsulotga fayl biriktirish', 'content' => 'Mahsulotni tahrirlash sahifasida "Qo\'shilgan fayllar" bo\'limidan drayver, qo\'llanma yoki texnik hujjatlarni biriktirishingiz mumkin. Fayllar avval Fayllar kutubxonasiga yuklangan bo\'lishi kerak.'],
                'ru' => ['title' => 'Прикрепление файлов к товару', 'content' => 'На странице редактирования товара в разделе «Прикреплённые файлы» можно прикрепить драйверы, инструкции или техническую документацию. Файлы должны быть предварительно загружены в библиотеку файлов.'],
                'en' => ['title' => 'Attaching files to a product', 'content' => 'On the product edit page, the "Attached files" section lets you attach drivers, manuals, or technical documents. Files must first be uploaded to the file library.'],
            ],
            [
                'section' => 'admin.companies', 'placement' => 'icon', 'sort_order' => 1,
                'uz' => ['title' => 'Kompaniyani tasdiqlash', 'content' => 'Yangi ro\'yxatdan o\'tgan kompaniyalar "Kutilayotganlar" bo\'limida ko\'rinadi. INN bo\'yicha tekshirib, rasmiy nomi va yuridik shaklini kiriting, so\'ng tasdiqlang yoki rad eting.'],
                'ru' => ['title' => 'Подтверждение компании', 'content' => 'Новые зарегистрированные компании отображаются в разделе «Ожидающие». Проверьте по ИНН, заполните официальное название и правовую форму, затем подтвердите или отклоните.'],
                'en' => ['title' => 'Approving a company', 'content' => 'Newly registered companies appear under "Pending". Verify by tax ID, fill in the official name and legal form, then approve or reject.'],
            ],
            [
                'section' => 'admin.files', 'placement' => 'tooltip', 'sort_order' => 1,
                'uz' => ['title' => 'Fayl kutubxonasi', 'content' => 'Bu yerga yuklangan har bir fayl bir nechta mahsulotga biriktirilishi mumkin — masalan, umumiy drayverni barcha modellar uchun qayta yuklamasdan ishlatish uchun.'],
                'ru' => ['title' => 'Библиотека файлов', 'content' => 'Каждый загруженный сюда файл можно прикрепить сразу к нескольким товарам — например, общий драйвер можно использовать для всех моделей без повторной загрузки.'],
                'en' => ['title' => 'File library', 'content' => 'Every file uploaded here can be attached to multiple products — for example, a shared driver can be reused across all models without re-uploading.'],
            ],
            [
                'section' => 'admin.banners', 'placement' => 'icon', 'sort_order' => 1,
                'uz' => ['title' => 'Bannerlarni sozlash', 'content' => 'Faol bannerlar bosh sahifada tartib raqami bo\'yicha ko\'rsatiladi. Bannerni vaqtincha yashirish uchun uni butunlay o\'chirishning hojati yo\'q — shunchaki faollikni o\'chiring.'],
                'ru' => ['title' => 'Настройка баннеров', 'content' => 'Активные баннеры показываются на главной странице по порядковому номеру. Чтобы временно скрыть баннер, не обязательно удалять его — просто отключите активность.'],
                'en' => ['title' => 'Configuring banners', 'content' => 'Active banners appear on the homepage in sort order. To temporarily hide a banner you don\'t need to delete it — just toggle it inactive.'],
            ],
        ];

        foreach ($articles as $def) {
            $slug = Str::slug($def['en']['title']);

            $article = HelpArticle::firstOrCreate(
                ['slug' => $slug],
                [
                    'section'    => $def['section'],
                    'placement'  => $def['placement'],
                    'is_active'  => true,
                    'sort_order' => $def['sort_order'],
                ]
            );

            foreach (['uz', 'ru', 'en'] as $lang) {
                $article->translations()->updateOrCreate(
                    ['lang' => $lang],
                    ['title' => $def[$lang]['title'], 'content' => $def[$lang]['content']]
                );
            }
        }
    }
}
