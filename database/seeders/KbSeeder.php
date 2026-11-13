<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\KbArticle;
use App\Models\Product;
use App\Services\Chatbot\KnowledgeBase;
use Illuminate\Database\Seeder;

/**
 * Starter knowledge base: what a driver / tool is, how to configure, how to fix.
 * Idempotent: articles are matched by slug and updated in place.
 *
 *   php artisan db:seed --class=KbSeeder
 */
class KbSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::pluck('id', 'model_number');
        $categories = Category::pluck('id', 'slug');

        foreach ($this->articles() as $i => $a) {
            $article = KbArticle::updateOrCreate(['slug' => $a['slug']], [
                'type'         => $a['type'],
                'difficulty'   => $a['difficulty'] ?? 'easy',
                'keywords'     => $a['keywords'] ?? null,
                'category_id'  => isset($a['category']) ? ($categories[$a['category']] ?? null) : null,
                'is_published' => true,
                'sort_order'   => $i,
            ]);

            foreach ($a['t'] as $lang => [$title, $excerpt, $body]) {
                $article->translations()->updateOrCreate(['lang' => $lang], [
                    'title'   => $title,
                    'excerpt' => $excerpt,
                    'body'    => $body,
                ]);
            }

            $ids = collect($a['models'] ?? [])->map(fn ($m) => $products[$m] ?? null)->filter()->values()->all();
            $article->products()->sync($ids);
        }

        KnowledgeBase::forgetCache();
    }

    private function articles(): array
    {
        $receipt = ['XP-Q890K', 'XP-Q809K', 'XP-V320N', 'XP-V330N', 'XP-58IIH', 'XP-T80A', 'XP-T80Q'];
        $label   = ['XP-420B', 'XP-423B', 'XP-428B', 'XP-490B', 'XP-490E', 'XP-365B', 'XP-235B'];
        $mobile  = ['DX5', 'OP1', 'MP3', 'XP-P202A', 'XP-P816'];

        return [

            // ── Glossary ───────────────────────────────────────
            [
                'slug' => 'what-is-a-printer-driver', 'type' => 'glossary',
                'keywords' => "drayver nima, драйвер что это, driver meaning, windows driver, usb driver, printer driver",
                't' => [
                    'uz' => ['Drayver nima?',
                        "Drayver — kompyuter (Windows, Android, Linux) va printer o'rtasidagi \"tarjimon\" dastur. Drayversiz operatsion tizim printerga buyruq bera olmaydi.",
                        "<p><strong>Drayver</strong> — operatsion tizimga printer bilan qanday \"gaplashish\"ni o'rgatuvchi kichik dastur. Siz 1C yoki Word'dan \"Chop etish\" tugmasini bosganingizda drayver hujjatni printer tushunadigan buyruqlarga (ESC/POS yoki TSPL) o'giradi.</p>
<h3>Nima uchun kerak</h3>
<ul><li>Windows printerni \"Qurilmalar va printerlar\" ro'yxatida ko'rsin;</li><li>1C, Poster, R-Keeper, Excel va boshqa dasturlar printerga chop etsin;</li><li>Qog'oz kengligi, zichlik, kesish kabi sozlamalar bir joyda bo'lsin.</li></ul>
<h3>Qachon kerak emas</h3>
<p>Kassa dasturi printerga <em>to'g'ridan-to'g'ri</em> ESC/POS buyruq yuborsa (ko'pincha Android kassalar, Poster, Uzum terminallari) — drayver kerak bo'lmaydi: faqat IP yoki Bluetooth orqali ulanish kifoya.</p>
<h3>Qayerdan olish</h3>
<p>Har bir model sahifasida <strong>«Drayverlar va fayllar»</strong> bo'limi bor. Faqat o'z modelingiz va operatsion tizimingiz uchun drayverni o'rnating. Noma'lum saytlardan drayver yuklamang — virus va noto'g'ri versiya xavfi bor.</p>"],
                    'ru' => ['Что такое драйвер?',
                        'Драйвер — программа-«переводчик» между компьютером (Windows, Android, Linux) и принтером. Без драйвера операционная система не может отправлять принтеру команды.',
                        "<p><strong>Драйвер</strong> — небольшая программа, которая объясняет операционной системе, как «разговаривать» с принтером. Когда вы нажимаете «Печать» в 1С или Word, драйвер переводит документ в команды, понятные принтеру (ESC/POS или TSPL).</p>
<h3>Зачем нужен</h3>
<ul><li>Чтобы Windows видела принтер в «Устройствах и принтерах»;</li><li>Чтобы 1С, Poster, R-Keeper, Excel и другие программы могли печатать;</li><li>Чтобы ширина бумаги, плотность, отрезка настраивались в одном месте.</li></ul>
<h3>Когда не нужен</h3>
<p>Если кассовая программа отправляет принтеру ESC/POS-команды <em>напрямую</em> (Android-кассы, Poster, терминалы Uzum) — драйвер не требуется: достаточно подключения по IP или Bluetooth.</p>
<h3>Где взять</h3>
<p>На странице каждой модели есть раздел <strong>«Драйверы и файлы»</strong>. Ставьте драйвер только для своей модели и своей ОС. Не скачивайте драйверы с незнакомых сайтов — риск вирусов и неподходящей версии.</p>"],
                    'en' => ['What is a printer driver?',
                        'A driver is the "translator" program between a computer (Windows, Android, Linux) and the printer. Without it the operating system cannot send commands to the printer.',
                        "<p>A <strong>driver</strong> is a small program that teaches the operating system how to talk to the printer. When you click Print in 1C or Word, the driver converts the document into commands the printer understands (ESC/POS or TSPL).</p>
<h3>Why you need it</h3>
<ul><li>So Windows lists the printer under Devices and Printers;</li><li>So 1C, Poster, R-Keeper, Excel and other apps can print;</li><li>So paper width, density and cutting are configured in one place.</li></ul>
<h3>When you don't</h3>
<p>If your POS software sends ESC/POS commands <em>directly</em> (most Android POS apps, Poster, Uzum terminals) no driver is needed, only an IP or Bluetooth connection.</p>
<h3>Where to get it</h3>
<p>Every model page has a <strong>Drivers &amp; files</strong> section. Install only the driver for your model and OS. Avoid unknown download sites: wrong versions and malware are common.</p>"],
                ],
            ],
            [
                'slug' => 'what-is-a-utility-tool', 'type' => 'glossary',
                'keywords' => "utility nima, утилита что это, tool, xprinter utility, printer setting tool, настройка через утилиту",
                't' => [
                    'uz' => ["Utilita (tool) nima va u drayverdan nimasi bilan farq qiladi?",
                        "Utilita — printerning o'zini sozlash dasturi (IP, zichlik, kod sahifasi, Bluetooth nomi). Drayver chop etish uchun, utilita sozlash uchun.",
                        "<p><strong>Utilita</strong> (inglizcha <em>tool</em>, «vosita») — printerning ichki sozlamalarini o'zgartiradigan dastur. Xprinter uchun asosiysi — <strong>Xprinter Printer Setting Tool</strong> (Utility).</p>
<table><thead><tr><th>Drayver</th><th>Utilita</th></tr></thead><tbody>
<tr><td>Dasturlardan chop etish uchun</td><td>Printerni sozlash uchun</td></tr>
<tr><td>Har doim o'rnatilgan turadi</td><td>Bir marta sozlab, yopib qo'yiladi</td></tr>
<tr><td>Hujjatni buyruqlarga o'giradi</td><td>IP, zichlik, kod sahifasi, tezlik, Bluetooth nomini yozadi</td></tr>
</tbody></table>
<h3>Utilita bilan nima qilish mumkin</h3>
<ul><li>Statik IP manzil berish yoki DHCP yoqish;</li><li>Chop etish zichligini (density) oshirish — xira chekda;</li><li>Kod sahifasini (codepage) o'zgartirish — tushunarsiz belgilarda;</li><li>Test sahifasini chop etish, firmware versiyasini ko'rish;</li><li>Bluetooth nomi va PIN-kodini o'zgartirish (mobil modellar).</li></ul>
<p>Utilitalar ro'yxati — <a href=\"/support/tool\">Utilitalar</a> bo'limida.</p>"],
                    'ru' => ['Что такое утилита (tool) и чем она отличается от драйвера?',
                        'Утилита — программа для настройки самого принтера (IP, плотность, кодовая страница, имя Bluetooth). Драйвер нужен для печати, утилита — для настройки.',
                        "<p><strong>Утилита</strong> (англ. <em>tool</em>, «инструмент») — программа, которая меняет внутренние настройки принтера. Для Xprinter основная — <strong>Xprinter Printer Setting Tool</strong> (Utility).</p>
<table><thead><tr><th>Драйвер</th><th>Утилита</th></tr></thead><tbody>
<tr><td>Чтобы печатать из программ</td><td>Чтобы настроить принтер</td></tr>
<tr><td>Стоит постоянно</td><td>Запускается один раз для настройки</td></tr>
<tr><td>Переводит документ в команды</td><td>Записывает IP, плотность, кодовую страницу, скорость, имя Bluetooth</td></tr>
</tbody></table>
<h3>Что можно сделать утилитой</h3>
<ul><li>Задать статический IP или включить DHCP;</li><li>Увеличить плотность печати (density) при бледном чеке;</li><li>Сменить кодовую страницу (codepage) при иероглифах;</li><li>Напечатать тестовую страницу, узнать версию прошивки;</li><li>Сменить имя и PIN Bluetooth (мобильные модели).</li></ul>
<p>Список утилит — в разделе <a href=\"/support/tool\">Утилиты</a>.</p>"],
                    'en' => ['What is a utility (tool) and how is it different from a driver?',
                        'A utility configures the printer itself (IP, density, codepage, Bluetooth name). The driver is for printing, the utility is for setup.',
                        "<p>A <strong>utility</strong> (or <em>tool</em>) is a program that changes the printer's internal settings. For Xprinter the main one is the <strong>Xprinter Printer Setting Tool</strong> (Utility).</p>
<table><thead><tr><th>Driver</th><th>Utility</th></tr></thead><tbody>
<tr><td>Lets applications print</td><td>Configures the printer</td></tr>
<tr><td>Stays installed</td><td>Run once to set things up</td></tr>
<tr><td>Converts documents to commands</td><td>Writes IP, density, codepage, speed, Bluetooth name</td></tr>
</tbody></table>
<h3>What a utility can do</h3>
<ul><li>Set a static IP or enable DHCP;</li><li>Increase print density when receipts are faint;</li><li>Change the codepage when you get garbage characters;</li><li>Print a test page, read the firmware version;</li><li>Change Bluetooth name and PIN (mobile models).</li></ul>
<p>See the <a href=\"/support/tool\">Tools</a> section for the list.</p>"],
                ],
            ],
            [
                'slug' => 'what-is-firmware', 'type' => 'glossary', 'difficulty' => 'medium',
                'keywords' => "firmware nima, прошивка, обновить прошивку, firmware update, proshivka",
                't' => [
                    'uz' => ['Firmware (proshivka) nima?',
                        "Firmware — printerning ichida ishlaydigan dastur. Uni yangilash xatolarni tuzatadi, lekin noto'g'ri fayl printerni ishdan chiqaradi — faqat servis markazida yangilang.",
                        "<p><strong>Firmware</strong> — printer platasida yozilgan \"operatsion tizim\". U tugmalar, sensorlar, kesgich va buyruqlarni boshqaradi. Test sahifasida versiyasi ko'rinadi (masalan, <code>V1.08</code>).</p>
<h3>Qachon yangilash kerak</h3>
<ul><li>Ishlab chiqaruvchi aniq xatoni tuzatgan bo'lsa (masalan, Wi-Fi uzilishi);</li><li>Yangi kod sahifasi yoki buyruq qo'llab-quvvatlash kerak bo'lsa.</li></ul>
<h3>Ehtiyot bo'ling</h3>
<p>Noto'g'ri modelga mo'ljallangan firmware printerni butunlay o'chirib qo'yishi mumkin va bu <strong>kafolat ostiga tushmaydi</strong>. Yangilashni <a href=\"/partners?type=service\">tekshirilgan servis markazi</a>ga topshiring.</p>"],
                    'ru' => ['Что такое прошивка (firmware)?',
                        'Прошивка — программа, работающая внутри принтера. Обновление исправляет ошибки, но неверный файл выводит принтер из строя — обновляйте только в сервис-центре.',
                        "<p><strong>Прошивка</strong> — «операционная система», записанная на плате принтера. Она управляет кнопками, датчиками, резаком и командами. Версия видна на тестовой странице (например, <code>V1.08</code>).</p>
<h3>Когда обновлять</h3>
<ul><li>Производитель исправил конкретную ошибку (например, обрывы Wi-Fi);</li><li>Нужна поддержка новой кодовой страницы или команды.</li></ul>
<h3>Осторожно</h3>
<p>Прошивка от другой модели может полностью «убить» принтер, и это <strong>не гарантийный случай</strong>. Доверьте обновление <a href=\"/partners?type=service\">проверенному сервис-центру</a>.</p>"],
                    'en' => ['What is firmware?',
                        'Firmware is the program running inside the printer. Updates fix bugs, but the wrong file can brick the printer, so update only at a service center.',
                        "<p><strong>Firmware</strong> is the operating system stored on the printer's board. It controls buttons, sensors, the cutter and command handling. The version is shown on the self-test page (e.g. <code>V1.08</code>).</p>
<h3>When to update</h3>
<ul><li>The manufacturer fixed a specific bug (e.g. Wi-Fi drops);</li><li>You need a new codepage or command supported.</li></ul>
<h3>Be careful</h3>
<p>Firmware for a different model can permanently disable the printer, and that is <strong>not covered by warranty</strong>. Let a <a href=\"/partners?type=service\">verified service center</a> do it.</p>"],
                ],
            ],
            [
                'slug' => 'esc-pos-tspl-zpl-explained', 'type' => 'glossary', 'difficulty' => 'medium',
                'keywords' => "esc/pos, escpos, tspl, zpl, buyruqlar tili, язык команд, protocol, emulation",
                't' => [
                    'uz' => ['ESC/POS, TSPL, ZPL — buyruq tillari nima?',
                        "Bular printer tushunadigan \"tillar\": ESC/POS — chek printerlari, TSPL va ZPL — etiket printerlari uchun. Dastur printer bilan bir tilda gaplashishi kerak.",
                        "<p>Printer matn va rasmlarni o'zi \"o'ylab\" chiqarmaydi — dastur unga buyruqlar yuboradi. Buyruqlar to'plami <strong>buyruq tili</strong> deyiladi.</p>
<table><thead><tr><th>Til</th><th>Qayerda</th><th>Xprinter modellari</th></tr></thead><tbody>
<tr><td><strong>ESC/POS</strong></td><td>Chek printerlari, kassa dasturlari (1C, Poster, R-Keeper, iiko)</td><td>XP-Q890K, XP-58IIH, XP-Q809K, XP-V320N, mobil DX5/OP1</td></tr>
<tr><td><strong>TSPL</strong></td><td>Etiket printerlari, shtrix-kod, Uzum/Yandex etiketlari</td><td>XP-420B, XP-428B, XP-365B, XP-235B</td></tr>
<tr><td><strong>ZPL</strong></td><td>Zebra standarti — ko'p logistika dasturlari shu tilda</td><td>Ko'p Xprinter etiket modellari ZPL emulyatsiyasini qo'llaydi</td></tr>
</tbody></table>
<h3>Amaliy maslahat</h3>
<p>Kassa dasturi \"ESC/POS printer\" so'rasa — istalgan Xprinter chek printeri bo'ladi. Logistika dasturi \"ZPL\" talab qilsa — modelda ZPL emulyatsiyasi borligini spetsifikatsiyada tekshiring.</p>"],
                    'ru' => ['ESC/POS, TSPL, ZPL — что такое языки команд?',
                        'Это «языки», которые понимает принтер: ESC/POS — для чековых, TSPL и ZPL — для этикеточных. Программа должна говорить с принтером на одном языке.',
                        "<p>Принтер не «придумывает» текст и картинки сам — программа отправляет ему команды. Набор команд называется <strong>языком команд</strong>.</p>
<table><thead><tr><th>Язык</th><th>Где используется</th><th>Модели Xprinter</th></tr></thead><tbody>
<tr><td><strong>ESC/POS</strong></td><td>Чековые принтеры, кассовые программы (1С, Poster, R-Keeper, iiko)</td><td>XP-Q890K, XP-58IIH, XP-Q809K, XP-V320N, мобильные DX5/OP1</td></tr>
<tr><td><strong>TSPL</strong></td><td>Этикеточные принтеры, штрихкоды, этикетки Uzum/Яндекс</td><td>XP-420B, XP-428B, XP-365B, XP-235B</td></tr>
<tr><td><strong>ZPL</strong></td><td>Стандарт Zebra — многие логистические программы говорят на нём</td><td>Большинство этикеточных Xprinter поддерживают эмуляцию ZPL</td></tr>
</tbody></table>
<h3>Практический совет</h3>
<p>Если кассовая программа просит «ESC/POS-принтер» — подойдёт любой чековый Xprinter. Если логистическая программа требует «ZPL» — проверьте в характеристиках, что модель поддерживает эмуляцию ZPL.</p>"],
                    'en' => ['ESC/POS, TSPL, ZPL: what are command languages?',
                        'These are the "languages" a printer understands: ESC/POS for receipt printers, TSPL and ZPL for label printers. Your software must speak the same one.',
                        "<p>A printer does not invent text and images on its own; the application sends it commands. The command set is called a <strong>command language</strong>.</p>
<table><thead><tr><th>Language</th><th>Used in</th><th>Xprinter models</th></tr></thead><tbody>
<tr><td><strong>ESC/POS</strong></td><td>Receipt printers, POS software (1C, Poster, R-Keeper, iiko)</td><td>XP-Q890K, XP-58IIH, XP-Q809K, XP-V320N, mobile DX5/OP1</td></tr>
<tr><td><strong>TSPL</strong></td><td>Label printers, barcodes, Uzum/Yandex labels</td><td>XP-420B, XP-428B, XP-365B, XP-235B</td></tr>
<tr><td><strong>ZPL</strong></td><td>Zebra's standard, used by many logistics systems</td><td>Most Xprinter label models offer ZPL emulation</td></tr>
</tbody></table>
<h3>Practical tip</h3>
<p>If POS software asks for an \"ESC/POS printer\", any Xprinter receipt model works. If logistics software requires ZPL, check the spec sheet for ZPL emulation.</p>"],
                ],
            ],

            // ── Drivers ────────────────────────────────────────
            [
                'slug' => 'install-windows-driver-receipt-printer', 'type' => 'driver', 'models' => $receipt, 'category' => 'receipt-printers',
                'keywords' => "windows drayver o'rnatish, установить драйвер windows, install driver, usb, com port, 80mm, 58mm, 1c printer",
                't' => [
                    'uz' => ["Chek printeri uchun Windows drayverini o'rnatish",
                        "Drayverni model sahifasidan yuklab oling, printerni USB orqali ulang, o'rnatuvchida portni (USB001 yoki COM) tanlang va test sahifasini chop eting.",
                        "<ol>
<li>Printerni elektrga ulang va yoqing. USB kabelni kompyuterga ulang (yoki LAN kabel — tarmoq orqali ulansa).</li>
<li>Model sahifasidagi <strong>«Drayverlar va fayllar»</strong> bo'limidan Windows drayverini yuklab oling va arxivni oching.</li>
<li><code>Setup.exe</code> ni <strong>administrator sifatida</strong> ishga tushiring.</li>
<li>Modelni tanlang (masalan, <em>XP-80C</em> — ko'p 80 mm modellar uchun umumiy drayver; 58 mm uchun <em>XP-58</em>).</li>
<li>Portni tanlang: USB — <code>USB001</code> (yoki birinchi bo'sh USB), tarmoq — <code>TCP/IP</code> va printer IP manzili, seriya — <code>COM</code> va tezlik <code>9600</code> yoki <code>115200</code>.</li>
<li>O'rnatish tugagach <strong>Qurilmalar va printerlar → printer → Printer xususiyatlari → Test sahifasini chop etish</strong>.</li>
</ol>
<h3>Kassa dasturiga ulash</h3>
<p>1C, Poster, R-Keeper, iiko'da printerni <strong>Windows printer</strong> sifatida tanlang yoki \"ESC/POS printer\" ni to'g'ridan-to'g'ri IP/COM orqali qo'shing. Qog'oz kengligini dasturda ham (80 yoki 58 mm) ko'rsating.</p>
<h3>Agar printer ro'yxatda ko'rinmasa</h3>
<p><a href=\"/support/article/printer-not-detected-usb\">Kompyuter printerni ko'rmayapti</a> maqolasiga qarang.</p>"],
                    'ru' => ['Установка драйвера Windows для чекового принтера',
                        'Скачайте драйвер со страницы модели, подключите принтер по USB, в установщике выберите порт (USB001 или COM) и напечатайте тестовую страницу.',
                        "<ol>
<li>Подключите принтер к электросети и включите. Подключите USB-кабель к компьютеру (или LAN-кабель, если принтер сетевой).</li>
<li>На странице модели в разделе <strong>«Драйверы и файлы»</strong> скачайте драйвер Windows и распакуйте архив.</li>
<li>Запустите <code>Setup.exe</code> <strong>от имени администратора</strong>.</li>
<li>Выберите модель (например, <em>XP-80C</em> — общий драйвер для большинства 80-мм моделей; для 58 мм — <em>XP-58</em>).</li>
<li>Выберите порт: USB — <code>USB001</code> (или первый свободный USB), сеть — <code>TCP/IP</code> и IP-адрес принтера, последовательный — <code>COM</code> и скорость <code>9600</code> или <code>115200</code>.</li>
<li>После установки: <strong>Устройства и принтеры → принтер → Свойства принтера → Пробная печать</strong>.</li>
</ol>
<h3>Подключение к кассовой программе</h3>
<p>В 1С, Poster, R-Keeper, iiko выберите принтер как <strong>Windows-принтер</strong> или добавьте «ESC/POS-принтер» напрямую по IP/COM. Укажите ширину бумаги (80 или 58 мм) и в программе.</p>
<h3>Если принтера нет в списке</h3>
<p>См. статью <a href=\"/support/article/printer-not-detected-usb\">Компьютер не видит принтер</a>.</p>"],
                    'en' => ['Installing the Windows driver for a receipt printer',
                        'Download the driver from the model page, connect the printer via USB, pick the port (USB001 or COM) in the installer and print a test page.',
                        "<ol>
<li>Plug in and power on the printer. Connect the USB cable to the computer (or a LAN cable for network models).</li>
<li>Download the Windows driver from the model page's <strong>Drivers &amp; files</strong> section and unzip it.</li>
<li>Run <code>Setup.exe</code> <strong>as administrator</strong>.</li>
<li>Choose the model (e.g. <em>XP-80C</em> is the shared driver for most 80 mm models; <em>XP-58</em> for 58 mm).</li>
<li>Choose the port: USB → <code>USB001</code> (or the first free USB), network → <code>TCP/IP</code> with the printer's IP, serial → <code>COM</code> at <code>9600</code> or <code>115200</code>.</li>
<li>Afterwards: <strong>Devices and Printers → printer → Printer properties → Print Test Page</strong>.</li>
</ol>
<h3>Connecting POS software</h3>
<p>In 1C, Poster, R-Keeper or iiko select the printer as a <strong>Windows printer</strong>, or add an \"ESC/POS printer\" directly by IP/COM. Set the paper width (80 or 58 mm) in the software too.</p>
<h3>If the printer is not listed</h3>
<p>See <a href=\"/support/article/printer-not-detected-usb\">Computer does not detect the printer</a>.</p>"],
                ],
            ],
            [
                'slug' => 'install-windows-driver-label-printer', 'type' => 'driver', 'models' => $label, 'category' => 'label-printers',
                'keywords' => "etiket printer drayver, драйвер этикеточного принтера, label driver, xp-420b driver, xp-365b driver, размер этикетки, etiket o'lchami",
                't' => [
                    'uz' => ["Etiket printeri uchun Windows drayverini o'rnatish va etiket o'lchamini belgilash",
                        "Drayverni o'rnatgach drayver sozlamalarida etiket o'lchamini (masalan, 58×40 mm) va sensorni (gap) ko'rsating — aks holda etiketlar siljiydi.",
                        "<ol>
<li>Etiket rulonini printerga joylang, qopqoqni yoping, printerni yoqing va USB orqali ulang.</li>
<li>Model sahifasidan Windows drayverini yuklab oling va <code>Setup.exe</code> ni administrator sifatida ishga tushiring. Modelni tanlang (XP-420B, XP-365B va h.k.), port — <code>USB001</code>.</li>
<li><strong>Qurilmalar va printerlar → printer → Chop etish parametrlari (Printing Preferences)</strong>.</li>
<li><strong>Page Setup → New</strong>: etiket o'lchamini mm da kiriting (masalan, kenglik 58, uzunlik 40; Uzum uchun 75×120 yoki 58×40 — marketplace talabiga qarang).</li>
<li><strong>Stock → Media type</strong>: oraliqli etiketlar uchun <em>Labels with gaps</em>, qora belgili uchun <em>Labels with marks</em>, uzluksiz lenta uchun <em>Continuous</em>.</li>
<li>Saqlang va <a href=\"/support/article/label-printer-calibration\">kalibrovka</a> qiling: FEED tugmasini 2–3 soniya bosib turing.</li>
</ol>
<h3>Dastur tomonida</h3>
<p>Uzum FBS, Yandex Market, 1C, Excel yoki etiket dizaynerida qog'oz o'lchamini <strong>drayverdagi bilan bir xil</strong> qiling. O'lcham mos kelmasa, etiketlar o'tkazib yuboriladi yoki yarmiga bosiladi.</p>"],
                    'ru' => ['Установка драйвера Windows для принтера этикеток и настройка размера этикетки',
                        'После установки драйвера задайте в его настройках размер этикетки (например, 58×40 мм) и тип датчика (gap) — иначе этикетки будут смещаться.',
                        "<ol>
<li>Установите рулон этикеток, закройте крышку, включите принтер и подключите по USB.</li>
<li>Скачайте драйвер Windows со страницы модели и запустите <code>Setup.exe</code> от имени администратора. Выберите модель (XP-420B, XP-365B и т. д.), порт — <code>USB001</code>.</li>
<li><strong>Устройства и принтеры → принтер → Настройка печати (Printing Preferences)</strong>.</li>
<li><strong>Page Setup → New</strong>: введите размер этикетки в мм (например, ширина 58, длина 40; для Uzum — 75×120 или 58×40, по требованиям маркетплейса).</li>
<li><strong>Stock → Media type</strong>: для этикеток с зазором — <em>Labels with gaps</em>, с чёрной метой — <em>Labels with marks</em>, для сплошной ленты — <em>Continuous</em>.</li>
<li>Сохраните и выполните <a href=\"/support/article/label-printer-calibration\">калибровку</a>: удерживайте FEED 2–3 секунды.</li>
</ol>
<h3>Со стороны программы</h3>
<p>В Uzum FBS, Яндекс Маркете, 1С, Excel или дизайнере этикеток размер бумаги должен быть <strong>таким же, как в драйвере</strong>. При несовпадении этикетки пропускаются или печатаются наполовину.</p>"],
                    'en' => ['Installing the Windows driver for a label printer and setting the label size',
                        'After installing the driver, set the label size (e.g. 58×40 mm) and sensor type (gap) in its preferences, otherwise labels will drift.',
                        "<ol>
<li>Load the label roll, close the lid, power on and connect via USB.</li>
<li>Download the Windows driver from the model page and run <code>Setup.exe</code> as administrator. Pick the model (XP-420B, XP-365B, etc.), port <code>USB001</code>.</li>
<li><strong>Devices and Printers → printer → Printing Preferences</strong>.</li>
<li><strong>Page Setup → New</strong>: enter the label size in mm (e.g. width 58, height 40; for Uzum 75×120 or 58×40 per marketplace requirements).</li>
<li><strong>Stock → Media type</strong>: <em>Labels with gaps</em> for gapped labels, <em>Labels with marks</em> for black-mark stock, <em>Continuous</em> for continuous rolls.</li>
<li>Save and <a href=\"/support/article/label-printer-calibration\">calibrate</a>: hold FEED for 2–3 seconds.</li>
</ol>
<h3>On the software side</h3>
<p>In Uzum FBS, Yandex Market, 1C, Excel or a label designer the paper size must <strong>match the driver</strong>. A mismatch skips labels or prints them half-way.</p>"],
                ],
            ],
            [
                'slug' => 'connect-mobile-printer-android-bluetooth', 'type' => 'driver', 'models' => $mobile, 'category' => 'mobile-printers',
                'keywords' => "bluetooth ulash, подключить по bluetooth, android printer, mobil printer, pin 0000, 1234, pairing",
                't' => [
                    'uz' => ['Mobil printerni Android telefonga Bluetooth orqali ulash',
                        "Telefonda Bluetooth → qurilmani toping (odatda modeli nomida), PIN 0000 yoki 1234, keyin kassa ilovasida printerni tanlang. Drayver kerak emas.",
                        "<ol>
<li>Printerni yoqing (ko'k indikator miltillaydi — ulanishga tayyor).</li>
<li>Telefonda <strong>Sozlamalar → Bluetooth → Yangi qurilma</strong>. Ro'yxatdan printerni tanlang (nomi <em>XP-…</em>, <em>DX5</em>, <em>MP3</em> yoki <em>Printer_XXXX</em>).</li>
<li>PIN so'ralsa <code>0000</code> yoki <code>1234</code> kiriting.</li>
<li>Kassa ilovasida (Poster, iiko, Uzum Seller, 1C Mobile, Loyverse) <strong>Printer qo'shish → Bluetooth</strong> va juftlangan printerni tanlang. Qog'oz kengligi: 58 mm (DX5, OP1, MP3) yoki 80 mm (XP-P816).</li>
<li>Test chek chiqaring.</li>
</ol>
<h3>Agar ulanmasa</h3>
<ul><li>Printer boshqa telefonga ulangan — u yerda Bluetooth'ni o'chiring, printerni qayta yoqing.</li><li>Telefon printerni ko'rmaydi — printerni telefonga yaqin qo'ying, 20 soniya kuting.</li><li>Ulanadi, lekin chop etmaydi — ilovada ESC/POS tanlanganini va 58/80 mm to'g'ri ko'rsatilganini tekshiring.</li></ul>
<p>Ko'p modellar <strong>USB</strong> orqali kompyuterga ham ulanadi — unda oddiy <a href=\"/support/article/install-windows-driver-receipt-printer\">Windows drayveri</a> kerak.</p>"],
                    'ru' => ['Подключение мобильного принтера к Android по Bluetooth',
                        'В телефоне: Bluetooth → найдите устройство (обычно в названии модель), PIN 0000 или 1234, затем выберите принтер в кассовом приложении. Драйвер не нужен.',
                        "<ol>
<li>Включите принтер (синий индикатор мигает — готов к сопряжению).</li>
<li>В телефоне <strong>Настройки → Bluetooth → Новое устройство</strong>. Выберите принтер (имя <em>XP-…</em>, <em>DX5</em>, <em>MP3</em> или <em>Printer_XXXX</em>).</li>
<li>Если спросит PIN — <code>0000</code> или <code>1234</code>.</li>
<li>В кассовом приложении (Poster, iiko, Uzum Seller, 1С Mobile, Loyverse): <strong>Добавить принтер → Bluetooth</strong>, выберите сопряжённый принтер. Ширина бумаги: 58 мм (DX5, OP1, MP3) или 80 мм (XP-P816).</li>
<li>Напечатайте тестовый чек.</li>
</ol>
<h3>Если не подключается</h3>
<ul><li>Принтер уже подключён к другому телефону — отключите Bluetooth там, перезапустите принтер.</li><li>Телефон не видит принтер — поднесите ближе, подождите 20 секунд.</li><li>Подключается, но не печатает — проверьте, что в приложении выбран ESC/POS и верная ширина 58/80 мм.</li></ul>
<p>Большинство моделей подключаются к компьютеру и по <strong>USB</strong> — тогда нужен обычный <a href=\"/support/article/install-windows-driver-receipt-printer\">драйвер Windows</a>.</p>"],
                    'en' => ['Connecting a mobile printer to Android over Bluetooth',
                        'On the phone: Bluetooth → find the device (usually named after the model), PIN 0000 or 1234, then pick the printer in your POS app. No driver needed.',
                        "<ol>
<li>Power on the printer (blue LED blinking = ready to pair).</li>
<li>On the phone: <strong>Settings → Bluetooth → Pair new device</strong>. Pick the printer (<em>XP-…</em>, <em>DX5</em>, <em>MP3</em> or <em>Printer_XXXX</em>).</li>
<li>If asked for a PIN, enter <code>0000</code> or <code>1234</code>.</li>
<li>In the POS app (Poster, iiko, Uzum Seller, 1C Mobile, Loyverse): <strong>Add printer → Bluetooth</strong>, choose the paired printer. Paper width: 58 mm (DX5, OP1, MP3) or 80 mm (XP-P816).</li>
<li>Print a test receipt.</li>
</ol>
<h3>If it won't connect</h3>
<ul><li>Already paired with another phone: turn Bluetooth off there and restart the printer.</li><li>Phone can't see it: move closer and wait 20 seconds.</li><li>Connects but won't print: make sure the app uses ESC/POS and the right 58/80 mm width.</li></ul>
<p>Most models also connect to a PC over <strong>USB</strong>, in which case the normal <a href=\"/support/article/install-windows-driver-receipt-printer\">Windows driver</a> applies.</p>"],
                ],
            ],

            // ── Tools ──────────────────────────────────────────
            [
                'slug' => 'xprinter-printer-setting-tool-overview', 'type' => 'tool', 'difficulty' => 'medium', 'models' => $receipt,
                'keywords' => "xprinter utility, printer setting tool, настройка принтера программа, ip o'rnatish, codepage, density, zichlik",
                't' => [
                    'uz' => ['Xprinter Printer Setting Tool (Utility) — nima qila oladi',
                        "Chek printerlari uchun asosiy utilita: IP manzil, zichlik, kod sahifasi, tezlik, kesgich, Bluetooth nomi. USB orqali ulab sozlash tavsiya etiladi.",
                        "<p><strong>Xprinter Printer Setting Tool</strong> — bepul Windows dasturi, model sahifasidagi «Drayverlar va fayllar» bo'limida (<em>Utility</em> turi).</p>
<h3>Ulanish</h3>
<ol><li>Printerni USB bilan ulang, dasturni ishga tushiring.</li><li><strong>Port</strong> → <em>USB</em> (yoki tarmoq uchun joriy IP). <strong>Connect / Test</strong> tugmasi test chek chiqarsa — ulangan.</li></ol>
<h3>Asosiy funksiyalar</h3>
<table><thead><tr><th>Bo'lim</th><th>Nima qiladi</th><th>Qachon kerak</th></tr></thead><tbody>
<tr><td>Ethernet / IP</td><td>Statik IP, maska, shlyuz yoki DHCP</td><td>Tarmoqdagi IP to'qnashuvi, yangi router</td></tr>
<tr><td>Density</td><td>Chop etish zichligi (1–8 yoki Light/Medium/Dark)</td><td>Xira chek</td></tr>
<tr><td>Codepage</td><td>Belgilar jadvali (CP866, CP1251, CP1252, UTF-8)</td><td>Kirill o'rniga tushunarsiz belgilar</td></tr>
<tr><td>Baud rate</td><td>COM tezligi 9600 / 19200 / 115200</td><td>Seriya ulanishda \"iyerogliflar\"</td></tr>
<tr><td>Cutter / Beep / Drawer</td><td>Avtomatik kesish, signal, kassa qutisi</td><td>Kesmayapti yoki ortiqcha signal</td></tr>
<tr><td>Bluetooth</td><td>Nomi va PIN</td><td>Bir xonada bir nechta printer</td></tr>
</tbody></table>
<p>Har bir o'zgarishdan keyin <strong>Set</strong> bosing va printerni qayta yoqing. Natijani <a href=\"/support/article/print-self-test-page\">test sahifasi</a>da tekshiring.</p>"],
                    'ru' => ['Xprinter Printer Setting Tool (Utility) — что умеет',
                        'Основная утилита для чековых принтеров: IP-адрес, плотность, кодовая страница, скорость, резак, имя Bluetooth. Настраивать лучше по USB.',
                        "<p><strong>Xprinter Printer Setting Tool</strong> — бесплатная программа для Windows, лежит на странице модели в «Драйверы и файлы» (тип <em>Utility</em>).</p>
<h3>Подключение</h3>
<ol><li>Подключите принтер по USB, запустите программу.</li><li><strong>Port</strong> → <em>USB</em> (или текущий IP для сети). Если по кнопке <strong>Connect / Test</strong> вышел тестовый чек — связь есть.</li></ol>
<h3>Основные функции</h3>
<table><thead><tr><th>Раздел</th><th>Что делает</th><th>Когда нужно</th></tr></thead><tbody>
<tr><td>Ethernet / IP</td><td>Статический IP, маска, шлюз или DHCP</td><td>Конфликт IP в сети, новый роутер</td></tr>
<tr><td>Density</td><td>Плотность печати (1–8 или Light/Medium/Dark)</td><td>Бледный чек</td></tr>
<tr><td>Codepage</td><td>Таблица символов (CP866, CP1251, CP1252, UTF-8)</td><td>Вместо кириллицы — иероглифы</td></tr>
<tr><td>Baud rate</td><td>Скорость COM 9600 / 19200 / 115200</td><td>«Иероглифы» при последовательном подключении</td></tr>
<tr><td>Cutter / Beep / Drawer</td><td>Автоотрезка, сигнал, денежный ящик</td><td>Не режет или лишние сигналы</td></tr>
<tr><td>Bluetooth</td><td>Имя и PIN</td><td>Несколько принтеров в одном зале</td></tr>
</tbody></table>
<p>После каждого изменения нажмите <strong>Set</strong> и перезапустите принтер. Проверьте результат на <a href=\"/support/article/print-self-test-page\">тестовой странице</a>.</p>"],
                    'en' => ['Xprinter Printer Setting Tool (Utility): what it does',
                        'The main utility for receipt printers: IP address, density, codepage, baud rate, cutter, Bluetooth name. Configure over USB when possible.',
                        "<p><strong>Xprinter Printer Setting Tool</strong> is a free Windows program found on the model page under Drivers &amp; files (type <em>Utility</em>).</p>
<h3>Connecting</h3>
<ol><li>Connect the printer by USB and launch the tool.</li><li><strong>Port</strong> → <em>USB</em> (or the current IP for network). If <strong>Connect / Test</strong> prints a test receipt, you are connected.</li></ol>
<h3>Main functions</h3>
<table><thead><tr><th>Section</th><th>What it does</th><th>When you need it</th></tr></thead><tbody>
<tr><td>Ethernet / IP</td><td>Static IP, mask, gateway or DHCP</td><td>IP conflict, new router</td></tr>
<tr><td>Density</td><td>Print darkness (1–8 or Light/Medium/Dark)</td><td>Faint receipts</td></tr>
<tr><td>Codepage</td><td>Character table (CP866, CP1251, CP1252, UTF-8)</td><td>Garbage instead of Cyrillic</td></tr>
<tr><td>Baud rate</td><td>COM speed 9600 / 19200 / 115200</td><td>Garbage over serial</td></tr>
<tr><td>Cutter / Beep / Drawer</td><td>Auto-cut, buzzer, cash drawer</td><td>No cutting or extra beeps</td></tr>
<tr><td>Bluetooth</td><td>Name and PIN</td><td>Several printers in one room</td></tr>
</tbody></table>
<p>Press <strong>Set</strong> after each change and restart the printer. Verify on the <a href=\"/support/article/print-self-test-page\">self-test page</a>.</p>"],
                ],
            ],
            [
                'slug' => 'label-design-software-for-xprinter', 'type' => 'tool', 'models' => $label,
                'keywords' => "etiket dizayner, программа для этикеток, label software, bartender, shtrix kod chop etish, печать штрихкода, uzum etiketka",
                't' => [
                    'uz' => ["Etiket dizaynerlari: shtrix-kod va etiketlarni qanday dastur bilan chop etish",
                        "Oddiy etiketlar uchun bepul Xprinter Label dizayneri yoki brauzerdan PDF (Uzum/Yandex) kifoya. Katta hajm uchun BarTender yoki 1C shablonlari.",
                        "<h3>Qanday vazifa — qanday vosita</h3>
<table><thead><tr><th>Vazifa</th><th>Vosita</th><th>Izoh</th></tr></thead><tbody>
<tr><td>Uzum FBS / Yandex Market etiketlari</td><td>Marketplace'dan PDF → Windows drayveri orqali chop etish</td><td>Drayverda etiket o'lchamini PDF o'lchamiga moslang</td></tr>
<tr><td>Narx yorliqlari, mahsulot etiketlari</td><td>Xprinter Label / <em>XPrinter Label Design</em> (bepul, model sahifasida)</td><td>Matn, shtrix-kod (EAN-13, Code128), QR, logotip; Excel'dan import</td></tr>
<tr><td>1C dan avtomatik etiketlar</td><td>1C «Etiketlar va narx yorliqlari» shabloni → Windows printer</td><td>Shablon o'lchami = drayver o'lchami</td></tr>
<tr><td>Ombor va seriyali chop etish</td><td>BarTender, NiceLabel, ZebraDesigner (ZPL emulyatsiyasi)</td><td>Pullik, lekin ma'lumotlar bazasidan ommaviy chop etadi</td></tr>
<tr><td>Dasturchi uchun</td><td>TSPL buyruqlari to'g'ridan-to'g'ri (USB/IP)</td><td>SDK va buyruq qo'llanmasi — Drayverlar bo'limida</td></tr>
</tbody></table>
<h3>Umumiy qoida</h3>
<p>Uch joyda bir xil o'lcham bo'lishi kerak: <strong>rulondagi etiket</strong>, <strong>drayver</strong>, <strong>dastur shabloni</strong>. Shundan keyin <a href=\"/support/article/label-printer-calibration\">kalibrovka</a> qiling.</p>"],
                    'ru' => ['Дизайнеры этикеток: какой программой печатать штрихкоды и этикетки',
                        'Для простых этикеток хватит бесплатного дизайнера Xprinter или PDF из браузера (Uzum/Яндекс). Для больших объёмов — BarTender или шаблоны 1С.',
                        "<h3>Какая задача — какой инструмент</h3>
<table><thead><tr><th>Задача</th><th>Инструмент</th><th>Примечание</th></tr></thead><tbody>
<tr><td>Этикетки Uzum FBS / Яндекс Маркет</td><td>PDF с маркетплейса → печать через драйвер Windows</td><td>Размер этикетки в драйвере = размер PDF</td></tr>
<tr><td>Ценники, товарные этикетки</td><td>Xprinter Label / <em>XPrinter Label Design</em> (бесплатно, на странице модели)</td><td>Текст, штрихкод (EAN-13, Code128), QR, логотип; импорт из Excel</td></tr>
<tr><td>Автоэтикетки из 1С</td><td>Шаблон 1С «Этикетки и ценники» → Windows-принтер</td><td>Размер шаблона = размер в драйвере</td></tr>
<tr><td>Склад и серийная печать</td><td>BarTender, NiceLabel, ZebraDesigner (эмуляция ZPL)</td><td>Платные, но печатают массово из базы</td></tr>
<tr><td>Для разработчика</td><td>Команды TSPL напрямую (USB/IP)</td><td>SDK и справочник команд — в разделе Драйверы</td></tr>
</tbody></table>
<h3>Общее правило</h3>
<p>Размер должен совпадать в трёх местах: <strong>этикетка в рулоне</strong>, <strong>драйвер</strong>, <strong>шаблон программы</strong>. После этого — <a href=\"/support/article/label-printer-calibration\">калибровка</a>.</p>"],
                    'en' => ['Label design software: what to print barcodes and labels with',
                        'For simple labels the free Xprinter label designer or a browser PDF (Uzum/Yandex) is enough. For volume use BarTender or 1C templates.',
                        "<h3>Task → tool</h3>
<table><thead><tr><th>Task</th><th>Tool</th><th>Note</th></tr></thead><tbody>
<tr><td>Uzum FBS / Yandex Market labels</td><td>PDF from the marketplace → print via Windows driver</td><td>Driver label size = PDF size</td></tr>
<tr><td>Price tags, product labels</td><td>Xprinter Label / <em>XPrinter Label Design</em> (free, on the model page)</td><td>Text, barcodes (EAN-13, Code128), QR, logo; Excel import</td></tr>
<tr><td>Automatic labels from 1C</td><td>1C \"Labels and price tags\" template → Windows printer</td><td>Template size = driver size</td></tr>
<tr><td>Warehouse and batch printing</td><td>BarTender, NiceLabel, ZebraDesigner (ZPL emulation)</td><td>Paid, but prints from databases in bulk</td></tr>
<tr><td>For developers</td><td>Raw TSPL commands (USB/IP)</td><td>SDK and command manual in the Drivers section</td></tr>
</tbody></table>
<h3>The one rule</h3>
<p>The size must match in three places: <strong>the label on the roll</strong>, <strong>the driver</strong>, <strong>the software template</strong>. Then <a href=\"/support/article/label-printer-calibration\">calibrate</a>.</p>"],
                ],
            ],

            // ── Settings ───────────────────────────────────────
            [
                'slug' => 'print-self-test-page', 'type' => 'settings', 'models' => array_merge($receipt, $label),
                'keywords' => "test sahifa, самотест, тестовая страница, self test, feed tugmasi, ip manzilni bilish, узнать ip принтера",
                't' => [
                    'uz' => ["Test sahifasini (self-test) chop etish va IP manzilni bilish",
                        "Printer o'chiq holda FEED tugmasini bosib turing, yoqing, 2–3 soniyadan keyin qo'yib yuboring. Test sahifasida IP, firmware, kod sahifasi va zichlik yoziladi.",
                        "<p>Test sahifasi — printerning \"pasporti\". Unda kompyutersiz ko'rish mumkin: <strong>IP manzil</strong>, firmware versiyasi, kod sahifasi, zichlik, interfeyslar, Bluetooth nomi.</p>
<h3>Chek printerlari (XP-Q890K, XP-58IIH, XP-Q809K va b.)</h3>
<ol><li>Printerni o'chiring. Qog'oz borligini tekshiring.</li><li><strong>FEED</strong> tugmasini bosib turgan holda printerni yoqing.</li><li>2–3 soniyadan keyin (yoki chop etish boshlanganda) tugmani qo'yib yuboring.</li></ol>
<h3>Etiket printerlari (XP-420B, XP-365B va b.)</h3>
<ol><li>Printer o'chiq, etiket joylangan.</li><li><strong>FEED</strong> ni bosib turib yoqing; indikator bir necha marta miltillagach qo'yib yuboring.</li><li>Printer avval kalibrovka qiladi, keyin konfiguratsiyani chop etadi.</li></ol>
<h3>Test sahifasida nimani o'qish</h3>
<ul><li><code>IP Address</code> — tarmoq orqali ulanish va utilita uchun;</li><li><code>Codepage</code> — kirill noto'g'ri chiqsa, bu qatorga qarang;</li><li><code>Density</code> — xira bosmada oshiring;</li><li><code>Firmware</code> — servis markazi so'raydi.</li></ul>
<p>Test sahifasi chiqmasa: qog'oz teskari joylangan yoki qopqoq yopilmagan — <a href=\"/support/article/printer-prints-blank-receipt\">Bo'sh chek</a> maqolasiga qarang.</p>"],
                    'ru' => ['Печать тестовой страницы (self-test) и как узнать IP-адрес',
                        'При выключенном принтере зажмите FEED, включите, через 2–3 секунды отпустите. На тестовой странице будут IP, прошивка, кодовая страница и плотность.',
                        "<p>Тестовая страница — «паспорт» принтера. Без компьютера на ней видно: <strong>IP-адрес</strong>, версию прошивки, кодовую страницу, плотность, интерфейсы, имя Bluetooth.</p>
<h3>Чековые принтеры (XP-Q890K, XP-58IIH, XP-Q809K и др.)</h3>
<ol><li>Выключите принтер. Убедитесь, что бумага заправлена.</li><li>Удерживая <strong>FEED</strong>, включите принтер.</li><li>Через 2–3 секунды (или когда началась печать) отпустите кнопку.</li></ol>
<h3>Этикеточные принтеры (XP-420B, XP-365B и др.)</h3>
<ol><li>Принтер выключен, этикетки заправлены.</li><li>Удерживая <strong>FEED</strong>, включите; когда индикатор несколько раз мигнёт — отпустите.</li><li>Принтер сначала откалибруется, затем напечатает конфигурацию.</li></ol>
<h3>Что читать на тестовой странице</h3>
<ul><li><code>IP Address</code> — для подключения по сети и утилиты;</li><li><code>Codepage</code> — если кириллица печатается неверно, смотрите сюда;</li><li><code>Density</code> — при бледной печати увеличьте;</li><li><code>Firmware</code> — спросит сервис-центр.</li></ul>
<p>Если тест не печатается: бумага вставлена вверх ногами или крышка не закрыта — см. <a href=\"/support/article/printer-prints-blank-receipt\">Пустой чек</a>.</p>"],
                    'en' => ['Printing the self-test page and finding the IP address',
                        'With the printer off, hold FEED, power on, release after 2–3 seconds. The self-test page lists IP, firmware, codepage and density.',
                        "<p>The self-test page is the printer's \"passport\". Without a computer it shows the <strong>IP address</strong>, firmware version, codepage, density, interfaces and Bluetooth name.</p>
<h3>Receipt printers (XP-Q890K, XP-58IIH, XP-Q809K, …)</h3>
<ol><li>Turn the printer off. Make sure paper is loaded.</li><li>Hold <strong>FEED</strong> and turn the printer on.</li><li>Release after 2–3 seconds (or once printing starts).</li></ol>
<h3>Label printers (XP-420B, XP-365B, …)</h3>
<ol><li>Printer off, labels loaded.</li><li>Hold <strong>FEED</strong> while powering on; release when the LED has blinked a few times.</li><li>The printer calibrates first, then prints its configuration.</li></ol>
<h3>What to read</h3>
<ul><li><code>IP Address</code> for network setup and the utility;</li><li><code>Codepage</code> when Cyrillic prints wrong;</li><li><code>Density</code> when print is faint;</li><li><code>Firmware</code> when a service center asks.</li></ul>
<p>If nothing prints: paper is upside down or the lid is open, see <a href=\"/support/article/printer-prints-blank-receipt\">Blank receipt</a>.</p>"],
                ],
            ],
            [
                'slug' => 'change-printer-ip-address', 'type' => 'settings', 'difficulty' => 'medium', 'models' => $receipt,
                'keywords' => "ip manzilni o'zgartirish, сменить ip адрес принтера, static ip, dhcp, 192.168.1.100, tarmoq printeri, сетевой принтер не печатает",
                't' => [
                    'uz' => ["Printerning IP manzilini o'zgartirish (LAN)",
                        "Xprinter Utility'da Ethernet bo'limida yangi IP ni kiriting va Set bosing. Zavod IP odatda 192.168.123.100 — routeringiz tarmog'iga moslang yoki DHCP yoqing.",
                        "<p>Zavoddan Xprinter tarmoq printerlari odatda <code>192.168.123.100</code> IP bilan keladi. Sizning routeringiz <code>192.168.1.x</code> yoki <code>192.168.0.x</code> bo'lsa, printer ko'rinmaydi — IP ni o'zgartirish kerak.</p>
<h3>1-usul: Utility orqali (USB bilan, eng ishonchli)</h3>
<ol><li>Printerni USB bilan ulang, <a href=\"/support/article/xprinter-printer-setting-tool-overview\">Printer Setting Tool</a> ni oching, Port → USB.</li><li><strong>Ethernet</strong> (yoki <em>Net Setting</em>) bo'limida: IP, masalan <code>192.168.1.200</code>; Subnet <code>255.255.255.0</code>; Gateway — router manzili (<code>192.168.1.1</code>).</li><li>Yoki <strong>DHCP: On</strong> — router o'zi IP beradi (lekin u o'zgarib turishi mumkin; kassa uchun statik IP yaxshiroq).</li><li><strong>Set</strong> bosing, printerni qayta yoqing, <a href=\"/support/article/print-self-test-page\">test sahifasida</a> yangi IP ni tekshiring.</li></ol>
<h3>2-usul: Brauzer orqali</h3>
<p>Kompyuterga vaqtincha <code>192.168.123.50</code> IP bering, brauzerda <code>http://192.168.123.100</code> oching (login/parol ko'pincha bo'sh yoki <code>admin</code>/<code>admin</code>), <em>Network</em> bo'limida IP ni o'zgartiring.</p>
<h3>Keyin</h3>
<p>Windows drayverida portni yangi IP ga o'zgartiring: Printer xususiyatlari → Portlar → Standard TCP/IP → Port sozlash. Kassa dasturida ham IP ni yangilang. Routerda shu IP ni printer MAC manziliga <strong>band qiling</strong> (DHCP reservation).</p>"],
                    'ru' => ['Как изменить IP-адрес принтера (LAN)',
                        'В Xprinter Utility в разделе Ethernet введите новый IP и нажмите Set. Заводской IP обычно 192.168.123.100 — приведите к сети вашего роутера или включите DHCP.',
                        "<p>С завода сетевые принтеры Xprinter обычно имеют IP <code>192.168.123.100</code>. Если ваш роутер раздаёт <code>192.168.1.x</code> или <code>192.168.0.x</code>, принтер не виден — IP нужно сменить.</p>
<h3>Способ 1: через утилиту (по USB, самый надёжный)</h3>
<ol><li>Подключите принтер по USB, откройте <a href=\"/support/article/xprinter-printer-setting-tool-overview\">Printer Setting Tool</a>, Port → USB.</li><li>В разделе <strong>Ethernet</strong> (или <em>Net Setting</em>): IP, например <code>192.168.1.200</code>; Subnet <code>255.255.255.0</code>; Gateway — адрес роутера (<code>192.168.1.1</code>).</li><li>Либо <strong>DHCP: On</strong> — роутер выдаст IP сам (но он может меняться; для кассы лучше статический).</li><li>Нажмите <strong>Set</strong>, перезапустите принтер, проверьте новый IP на <a href=\"/support/article/print-self-test-page\">тестовой странице</a>.</li></ol>
<h3>Способ 2: через браузер</h3>
<p>Временно задайте компьютеру IP <code>192.168.123.50</code>, откройте в браузере <code>http://192.168.123.100</code> (логин/пароль часто пустые или <code>admin</code>/<code>admin</code>), в разделе <em>Network</em> смените IP.</p>
<h3>После смены</h3>
<p>В драйвере Windows измените порт на новый IP: Свойства принтера → Порты → Standard TCP/IP → Настроить порт. Обновите IP и в кассовой программе. В роутере <strong>закрепите</strong> этот IP за MAC-адресом принтера (DHCP reservation).</p>"],
                    'en' => ['Changing the printer IP address (LAN)',
                        'In Xprinter Utility, Ethernet section, enter the new IP and press Set. Factory IP is usually 192.168.123.100: match your router subnet or enable DHCP.',
                        "<p>Xprinter network printers ship with IP <code>192.168.123.100</code>. If your router uses <code>192.168.1.x</code> or <code>192.168.0.x</code>, the printer is invisible until you change its IP.</p>
<h3>Method 1: Utility over USB (most reliable)</h3>
<ol><li>Connect by USB, open the <a href=\"/support/article/xprinter-printer-setting-tool-overview\">Printer Setting Tool</a>, Port → USB.</li><li>In <strong>Ethernet</strong> (or <em>Net Setting</em>): IP e.g. <code>192.168.1.200</code>; Subnet <code>255.255.255.0</code>; Gateway = router (<code>192.168.1.1</code>).</li><li>Or <strong>DHCP: On</strong> so the router assigns one (it may change; a static IP is better for POS).</li><li>Press <strong>Set</strong>, restart the printer, verify on the <a href=\"/support/article/print-self-test-page\">self-test page</a>.</li></ol>
<h3>Method 2: web interface</h3>
<p>Temporarily set your PC to <code>192.168.123.50</code>, open <code>http://192.168.123.100</code> (login often blank or <code>admin</code>/<code>admin</code>) and change the IP under <em>Network</em>.</p>
<h3>Afterwards</h3>
<p>Point the Windows driver at the new IP: Printer properties → Ports → Standard TCP/IP → Configure Port. Update the POS software too. <strong>Reserve</strong> the IP for the printer's MAC in the router (DHCP reservation).</p>"],
                ],
            ],
            [
                'slug' => 'label-printer-calibration', 'type' => 'settings', 'models' => $label, 'category' => 'label-printers',
                'keywords' => "kalibrovka, калибровка этикеток, gap sensor, etiket o'tkazib yuboradi, пропускает этикетки, печатает между этикетками",
                't' => [
                    'uz' => ["Etiket printerini kalibrovka qilish (gap / black mark)",
                        "Yangi rulon qo'ygandan keyin FEED tugmasini 2–3 soniya bosib turing — printer etiket oralig'ini o'lchaydi. Bu etiketlarning siljishi va o'tkazib yuborilishini tuzatadi.",
                        "<p><strong>Kalibrovka</strong> — printer sensor yordamida etiketning uzunligi va etiketlar orasidagi bo'shliqni (gap) o'lchaydi. Har safar <em>boshqa o'lchamdagi rulon</em> qo'yganda kerak.</p>
<h3>Tez usul (tugma bilan)</h3>
<ol><li>Printer yoqilgan, rulon joylangan, qopqoq yopilgan.</li><li><strong>FEED</strong> tugmasini 2–3 soniya bosib turing (indikator miltillay boshlaydi), qo'yib yuboring.</li><li>Printer 2–4 etiketni chiqaradi va to'xtaydi — kalibrovka tugadi. Chiqqan etiketlarni tashlang.</li></ol>
<h3>Utility / drayver orqali</h3>
<p>Label utilitasida yoki drayverda <em>Printing Preferences → Tools → Calibrate</em>. Sensor turini to'g'ri tanlang: <strong>Gap</strong> (oraliqli oq etiketlar), <strong>Black mark</strong> (orqasida qora chiziq), <strong>Continuous</strong> (uzluksiz lenta).</p>
<h3>Agar yordam bermasa</h3>
<ul><li>Drayverdagi etiket o'lchami haqiqiy rulonga mos emas — <a href=\"/support/article/install-windows-driver-label-printer\">o'lchamni tuzating</a>.</li><li>Sensor chang bo'lgan — qopqoqni ochib, sensor oynasini quruq paxta bilan arting.</li><li>Shaffof yoki juda qora etiketlar — sensor ko'rmaydi, black mark rejimi yoki boshqa etiket kerak.</li></ul>"],
                    'ru' => ['Калибровка принтера этикеток (gap / black mark)',
                        'После установки нового рулона удерживайте FEED 2–3 секунды — принтер измерит зазор между этикетками. Это лечит смещение и пропуск этикеток.',
                        "<p><strong>Калибровка</strong> — принтер датчиком измеряет длину этикетки и зазор между этикетками (gap). Нужна каждый раз, когда ставите <em>рулон другого размера</em>.</p>
<h3>Быстрый способ (кнопкой)</h3>
<ol><li>Принтер включён, рулон заправлен, крышка закрыта.</li><li>Удерживайте <strong>FEED</strong> 2–3 секунды (индикатор замигает), отпустите.</li><li>Принтер выдаст 2–4 этикетки и остановится — калибровка завершена. Эти этикетки выбросьте.</li></ol>
<h3>Через утилиту / драйвер</h3>
<p>В утилите для этикеток или в драйвере: <em>Printing Preferences → Tools → Calibrate</em>. Правильно выберите тип датчика: <strong>Gap</strong> (белые этикетки с зазором), <strong>Black mark</strong> (чёрная метка с обратной стороны), <strong>Continuous</strong> (сплошная лента).</p>
<h3>Если не помогло</h3>
<ul><li>Размер этикетки в драйвере не совпадает с рулоном — <a href=\"/support/article/install-windows-driver-label-printer\">исправьте размер</a>.</li><li>Запылился датчик — откройте крышку и протрите окошко датчика сухой ватной палочкой.</li><li>Прозрачные или очень тёмные этикетки — датчик их не видит; нужен режим black mark или другие этикетки.</li></ul>"],
                    'en' => ['Calibrating a label printer (gap / black mark)',
                        'After loading a new roll, hold FEED for 2–3 seconds so the printer measures the label gap. This fixes drifting and skipped labels.',
                        "<p><strong>Calibration</strong> lets the printer measure label length and the gap between labels with its sensor. Do it every time you load a <em>roll of a different size</em>.</p>
<h3>Quick way (button)</h3>
<ol><li>Printer on, roll loaded, lid closed.</li><li>Hold <strong>FEED</strong> for 2–3 seconds (LED starts blinking), release.</li><li>The printer feeds 2–4 labels and stops. Done; discard those labels.</li></ol>
<h3>Via utility / driver</h3>
<p>In the label utility or driver: <em>Printing Preferences → Tools → Calibrate</em>. Pick the right sensor: <strong>Gap</strong> (white labels with gaps), <strong>Black mark</strong> (black line on the back), <strong>Continuous</strong>.</p>
<h3>If it doesn't help</h3>
<ul><li>Driver label size doesn't match the roll: <a href=\"/support/article/install-windows-driver-label-printer\">fix the size</a>.</li><li>Dusty sensor: open the lid and wipe the sensor window with a dry cotton swab.</li><li>Transparent or very dark labels: the sensor can't see them; use black-mark mode or different stock.</li></ul>"],
                ],
            ],
            [
                'slug' => 'set-paper-width-58-80', 'type' => 'settings', 'models' => $receipt, 'category' => 'receipt-printers',
                'keywords' => "qog'oz kengligi 58 80, ширина бумаги 58 80, chek yarmi kesilgan, обрезается текст чека, 80 мм принтер 58 бумага",
                't' => [
                    'uz' => ["Qog'oz kengligini 58 / 80 mm ga sozlash",
                        "Drayverda qog'oz o'lchamini (80 mm = 72 mm bosma maydoni, 58 mm = 48 mm) va kassa dasturida chek kengligini bir xil qiling. 80 mm printerga 58 mm lenta qo'yilsa — ichki cheklovchi va drayver sozlamasi.",
                        "<h3>Qayerda sozlanadi</h3>
<ol><li><strong>Windows drayveri</strong>: Chop etish parametrlari → Qog'oz → <em>80(72) × 297 mm</em> yoki <em>58(48) × 297 mm</em>. Qavsdagi raqam — haqiqiy bosma maydoni.</li><li><strong>Kassa dasturi</strong> (1C, Poster, iiko): chek shablonida kenglik — 42–48 belgi (80 mm) yoki 32 belgi (58 mm).</li><li><strong>Printerning o'zi</strong>: 80 mm modelga 58 mm rulon qo'yish uchun qog'oz bo'limidagi plastik <em>cheklovchi</em>ni (spacer) o'rnating va utilitada <em>Paper width 58</em> ni tanlang (bor modellarda).</li></ol>
<h3>Belgilar</h3>
<ul><li>Chekning o'ng tomoni kesilgan — dastur 80 mm, qog'oz 58 mm.</li><li>Chek tor, chapda katta bo'sh joy — dastur 58 mm, qog'oz 80 mm.</li></ul>
<p>Qog'oz turi: <strong>termo chek lentasi</strong> 57×30/40 mm (58 mm printerlar) va 80×80 mm (80 mm printerlar). Issiqlikka sezgir tomoni tashqarida bo'lishi kerak.</p>"],
                    'ru' => ['Настройка ширины бумаги 58 / 80 мм',
                        'В драйвере задайте размер бумаги (80 мм = 72 мм зоны печати, 58 мм = 48 мм) и ту же ширину чека в кассовой программе. Для 58-мм ленты в 80-мм принтере — ограничитель и настройка драйвера.',
                        "<h3>Где настраивается</h3>
<ol><li><strong>Драйвер Windows</strong>: Настройка печати → Бумага → <em>80(72) × 297 мм</em> или <em>58(48) × 297 мм</em>. Число в скобках — реальная зона печати.</li><li><strong>Кассовая программа</strong> (1С, Poster, iiko): в шаблоне чека ширина — 42–48 символов (80 мм) или 32 символа (58 мм).</li><li><strong>Сам принтер</strong>: чтобы поставить 58-мм рулон в 80-мм модель, установите пластиковый <em>ограничитель</em> (spacer) в отсеке бумаги и выберите в утилите <em>Paper width 58</em> (где доступно).</li></ol>
<h3>Симптомы</h3>
<ul><li>Правый край чека обрезан — программа настроена на 80 мм, бумага 58 мм.</li><li>Чек узкий, слева большое пустое поле — программа на 58 мм, бумага 80 мм.</li></ul>
<p>Бумага: <strong>чековая термолента</strong> 57×30/40 мм (58-мм принтеры) и 80×80 мм (80-мм принтеры). Термочувствительная сторона — снаружи рулона.</p>"],
                    'en' => ['Setting paper width to 58 / 80 mm',
                        'Set the paper size in the driver (80 mm = 72 mm printable, 58 mm = 48 mm) and the same receipt width in the POS software. For 58 mm rolls in an 80 mm printer, use the spacer and driver setting.',
                        "<h3>Where it is set</h3>
<ol><li><strong>Windows driver</strong>: Printing Preferences → Paper → <em>80(72) × 297 mm</em> or <em>58(48) × 297 mm</em>. The number in brackets is the real printable width.</li><li><strong>POS software</strong> (1C, Poster, iiko): receipt template width 42–48 characters (80 mm) or 32 characters (58 mm).</li><li><strong>The printer itself</strong>: to use a 58 mm roll in an 80 mm model, fit the plastic <em>spacer</em> in the paper bay and select <em>Paper width 58</em> in the utility (where available).</li></ol>
<h3>Symptoms</h3>
<ul><li>Right edge cut off: software set to 80 mm, paper is 58 mm.</li><li>Narrow receipt with a wide left margin: software set to 58 mm, paper is 80 mm.</li></ul>
<p>Paper: <strong>thermal receipt rolls</strong> 57×30/40 mm (58 mm printers) and 80×80 mm (80 mm printers). The heat-sensitive side faces out.</p>"],
                ],
            ],

            // ── Troubleshooting ────────────────────────────────
            [
                'slug' => 'printer-prints-blank-receipt', 'type' => 'troubleshooting', 'models' => $receipt,
                'keywords' => "bo'sh chek, oq chek, пустой чек, белый чек, prints blank, ничего не печатает но бумага выходит, qog'oz chiqadi lekin yozuv yo'q",
                't' => [
                    'uz' => ["Printer bo'sh (oq) chek chiqaradi",
                        "90% hollarda qog'oz teskari joylangan. Rulonni aylantiring: termo qatlam printer boshiga (yuqoriga) qarashi kerak. Tirnoq bilan tirnaganda qora iz qoladigan tomon — to'g'ri tomon.",
                        "<h3>1. Qog'oz teskari (eng ko'p uchraydi)</h3>
<p>Rulonni olib, aylantirib qo'ying. Tekshirish: qog'ozni tirnoq bilan tez tirnang — <strong>qora iz qolgan tomon</strong> termo qatlam, u pastdan chiqib printer boshiga qaragan bo'lishi kerak.</p>
<h3>2. Qog'oz termo emas</h3>
<p>Oddiy ofis qog'ozi yoki faks qog'ozi bilan termoprinter bosmaydi. Faqat <strong>termo chek lentasi</strong> ishlaydi.</p>
<h3>3. Qopqoq to'liq yopilmagan</h3>
<p>Qopqoqni ikkala tomondan bosib, \"shchelk\" eshitilguncha yoping. Ko'p modellar qopqoq ochiq bo'lsa, qog'ozni suradi, lekin bosmaydi.</p>
<h3>4. Zichlik minimalda</h3>
<p>Utilitada <em>Density</em> ni oshiring (<a href=\"/support/article/xprinter-printer-setting-tool-overview\">qanday</a>). Test sahifasi ham oq chiqsa — bu 1–3 bandlar.</p>
<h3>5. Dastur bo'sh hujjat yuboradi</h3>
<p>Test sahifasi normal chiqsa, muammo dasturda: shrift/kod sahifasi noto'g'ri yoki drayver boshqa modelga. <a href=\"/support/article/print-self-test-page\">Test sahifasini</a> chop eting va natijani solishtiring.</p>
<h3>6. Bosh (head) ishdan chiqqan</h3>
<p>Barchasi to'g'ri, lekin oq — bosh issiq elementi yonib ketgan bo'lishi mumkin. <a href=\"/partners?type=service\">Servis markaziga</a> murojaat qiling; kafolat 12 oy.</p>"],
                    'ru' => ['Принтер печатает пустой (белый) чек',
                        'В 90% случаев бумага вставлена вверх ногами. Переверните рулон: термослой должен смотреть на головку (вверх). Правильная сторона — та, на которой ноготь оставляет чёрный след.',
                        "<h3>1. Бумага перевёрнута (самая частая причина)</h3>
<p>Достаньте рулон и переверните. Проверка: быстро проведите ногтем по бумаге — сторона, где <strong>остаётся чёрный след</strong>, термочувствительная, она должна выходить снизу и смотреть на головку.</p>
<h3>2. Бумага не термо</h3>
<p>Обычная офисная или факсовая бумага в термопринтере не печатается. Работает только <strong>чековая термолента</strong>.</p>
<h3>3. Крышка не закрыта до конца</h3>
<p>Прижмите крышку с обеих сторон до щелчка. Многие модели при открытой крышке протягивают бумагу, но не печатают.</p>
<h3>4. Плотность на минимуме</h3>
<p>Увеличьте <em>Density</em> в утилите (<a href=\"/support/article/xprinter-printer-setting-tool-overview\">как</a>). Если тестовая страница тоже белая — это пункты 1–3.</p>
<h3>5. Программа отправляет пустой документ</h3>
<p>Если тестовая страница печатается нормально, проблема в программе: неверный шрифт/кодовая страница или драйвер другой модели. Напечатайте <a href=\"/support/article/print-self-test-page\">тестовую страницу</a> и сравните.</p>
<h3>6. Вышла из строя головка</h3>
<p>Всё верно, но чек белый — возможно, сгорел нагревательный элемент головки. Обратитесь в <a href=\"/partners?type=service\">сервис-центр</a>; гарантия 12 месяцев.</p>"],
                    'en' => ['Printer prints a blank (white) receipt',
                        '90% of the time the paper is upside down. Flip the roll: the thermal side must face the print head (up). The correct side turns black when you scratch it with a fingernail.',
                        "<h3>1. Paper is upside down (most common)</h3>
<p>Take the roll out and flip it. Test: scratch the paper quickly with a fingernail; the side that <strong>leaves a black mark</strong> is thermal and must come out from below, facing the head.</p>
<h3>2. Not thermal paper</h3>
<p>Plain office or fax paper will not print in a thermal printer. Only <strong>thermal receipt rolls</strong> work.</p>
<h3>3. Lid not fully closed</h3>
<p>Press the lid down on both sides until it clicks. Many models feed paper with the lid open but don't print.</p>
<h3>4. Density at minimum</h3>
<p>Raise <em>Density</em> in the utility (<a href=\"/support/article/xprinter-printer-setting-tool-overview\">how</a>). If the self-test is blank too, it is points 1–3.</p>
<h3>5. Software sends an empty document</h3>
<p>If the self-test prints fine, the issue is in the software: wrong font/codepage or a driver for another model. Print the <a href=\"/support/article/print-self-test-page\">self-test page</a> and compare.</p>
<h3>6. Print head failure</h3>
<p>Everything is right but still white: the heating element may be dead. Contact a <a href=\"/partners?type=service\">service center</a>; warranty is 12 months.</p>"],
                ],
            ],
            [
                'slug' => 'faint-or-light-print', 'type' => 'troubleshooting', 'models' => array_merge($receipt, $label),
                'keywords' => "xira chek, och rangda bosadi, бледная печать, светлая печать, faint print, light print, polosa, полосы на чеке, boshni tozalash, чистка головки",
                't' => [
                    'uz' => ["Xira, och rangli bosma yoki chekda oq chiziqlar",
                        "Zichlikni (density) oshiring, printer boshini izopropil spirt bilan tozalang, sifatli termo qog'oz ishlating. Oq vertikal chiziq — boshda chang yoki yonib ketgan nuqta.",
                        "<h3>Xira bosma</h3>
<ol><li><strong>Zichlikni oshiring</strong>: utilitada <em>Density</em> → o'rtadan yuqori (yoki drayverda <em>Print darkness</em>). Juda yuqori zichlik boshni tez eskirtiradi — kerakli minimumda qoldiring.</li><li><strong>Qog'oz sifati</strong>: arzon lentalar och chiqadi va boshni ifloslaydi. Tekshirilgan dilerlardan lenta oling.</li><li><strong>Tezlik</strong>: ba'zi modellarda tezlikni pasaytirish bosmani quyuqlashtiradi.</li></ol>
<h3>Oq vertikal chiziqlar</h3>
<ol><li>Printerni o'chiring, qopqoqni oching, 1 daqiqa sovushini kuting.</li><li>Paxta tayoqchani <strong>izopropil spirtga</strong> (70%+) namlang, printer boshining (qora chiziq, qog'oz tegadigan joy) bo'ylab bir yo'nalishda arting. Suv, atseton, metall ishlatmang.</li><li>Rezina valikni ham arting (unda yelim/chang yig'iladi).</li><li>Quriguncha kuting, qog'ozni qo'ying, test sahifasi chiqaring.</li></ol>
<p>Tozalashdan keyin ham bir xil joyda oq chiziq — boshning o'sha nuqtasi yonib ketgan. Bosh almashtiriladi — <a href=\"/partners?type=service\">servis markazi</a>.</p>
<h3>Profilaktika</h3>
<p>Har 1–2 oyda (yoki har 5–10 rulon) boshni tozalang. Etiket printerlarida yelim qoldiqlari tez yig'iladi — tez-tez tozalang.</p>"],
                    'ru' => ['Бледная, светлая печать или белые полосы на чеке',
                        'Увеличьте плотность (density), протрите головку изопропиловым спиртом, используйте качественную термобумагу. Белая вертикальная полоса — пыль на головке или сгоревшая точка.',
                        "<h3>Бледная печать</h3>
<ol><li><strong>Увеличьте плотность</strong>: в утилите <em>Density</em> → выше среднего (или <em>Print darkness</em> в драйвере). Слишком высокая плотность быстро изнашивает головку — оставьте необходимый минимум.</li><li><strong>Качество бумаги</strong>: дешёвая лента печатается бледно и пачкает головку. Берите ленту у проверенных дилеров.</li><li><strong>Скорость</strong>: на некоторых моделях снижение скорости делает печать темнее.</li></ol>
<h3>Белые вертикальные полосы</h3>
<ol><li>Выключите принтер, откройте крышку, дайте остыть 1 минуту.</li><li>Смочите ватную палочку <strong>изопропиловым спиртом</strong> (70%+) и протрите головку (чёрная полоска, куда прижимается бумага) в одном направлении. Не используйте воду, ацетон, металл.</li><li>Протрите и резиновый вал (на нём собирается клей и пыль).</li><li>Дождитесь высыхания, заправьте бумагу, напечатайте тест.</li></ol>
<p>Если после чистки полоса на том же месте — эта точка головки сгорела. Головка заменяется в <a href=\"/partners?type=service\">сервис-центре</a>.</p>
<h3>Профилактика</h3>
<p>Чистите головку каждые 1–2 месяца (или каждые 5–10 рулонов). На этикеточных принтерах остатки клея копятся быстрее — чистите чаще.</p>"],
                    'en' => ['Faint or light print, or white lines on the receipt',
                        'Raise the density, clean the print head with isopropyl alcohol, use quality thermal paper. A white vertical line means dust on the head or a dead dot.',
                        "<h3>Faint print</h3>
<ol><li><strong>Raise density</strong>: utility <em>Density</em> → above middle (or <em>Print darkness</em> in the driver). Too high wears the head out fast, so keep the needed minimum.</li><li><strong>Paper quality</strong>: cheap rolls print light and dirty the head. Buy paper from verified dealers.</li><li><strong>Speed</strong>: on some models lowering speed darkens the print.</li></ol>
<h3>White vertical lines</h3>
<ol><li>Power off, open the lid, let it cool for a minute.</li><li>Wet a cotton swab with <strong>isopropyl alcohol</strong> (70%+) and wipe the print head (the black strip the paper presses against) in one direction. No water, acetone or metal.</li><li>Wipe the rubber roller too (glue and dust collect there).</li><li>Let it dry, load paper, print a test.</li></ol>
<p>If the line is in the same place after cleaning, that dot of the head is dead. Head replacement is done by a <a href=\"/partners?type=service\">service center</a>.</p>
<h3>Prevention</h3>
<p>Clean the head every 1–2 months (or every 5–10 rolls). Label printers collect adhesive faster, so clean more often.</p>"],
                ],
            ],
            [
                'slug' => 'garbage-characters-instead-of-text', 'type' => 'troubleshooting', 'difficulty' => 'medium', 'models' => $receipt,
                'keywords' => "tushunarsiz belgilar, iyeroglif, иероглифы, кракозябры, вместо кириллицы символы, garbage characters, codepage cp866 cp1251, baud rate",
                't' => [
                    'uz' => ["Chekda tushunarsiz belgilar (iyerogliflar) chiqadi",
                        "Kod sahifasi (codepage) mos kelmaydi yoki COM tezligi noto'g'ri. Utilitada codepage'ni CP866 / CP1251 (kirill) ga qo'ying, COM da 9600 yoki 115200 ni tekshiring, drayver o'z modelingizga bo'lsin.",
                        "<h3>Hamma joyda belgilar, hatto raqamlar ham</h3>
<p>Bu <strong>ulanish tezligi</strong> (baud rate) yoki noto'g'ri drayver. </p>
<ul><li>COM/seriya ulanishda: Windows → Qurilma menejeri → COM port → <em>9600</em> va printerdagi tezlik bir xil bo'lsin (test sahifasida yozilgan).</li><li>USB da: drayver boshqa modelga o'rnatilgan — o'chirib, <a href=\"/support/article/install-windows-driver-receipt-printer\">o'z modelingiz drayverini</a> qo'ying.</li><li>Etiket printeriga chek drayveri (yoki aksincha) o'rnatilgan — TSPL va ESC/POS aralashgan.</li></ul>
<h3>Lotin to'g'ri, kirill noto'g'ri</h3>
<p>Bu <strong>kod sahifasi</strong>. Utilitada <em>Codepage</em> → <code>CP866</code> (ko'p 1C konfiguratsiyalari) yoki <code>CP1251</code> (Windows-1251). Dasturda ham shu kod sahifasini tanlang. Yangi modellar <code>UTF-8</code> ni ham qo'llaydi — ikkalasi bir xil bo'lsin.</p>
<h3>O'zbek harflari (oʻ, gʻ, ʼ) noto'g'ri</h3>
<p>Kirill kod sahifalarida bu harflar yo'q. Yechim: dastur chekni <strong>grafik (rasm) rejimida</strong> yuborsin (1C da «Печать как картинка», Poster/iiko da o'zi shunday) yoki UTF-8 qo'llaydigan modelda UTF-8.</p>
<h3>Chek o'rtasida bir qancha tasodifiy belgi</h3>
<p>Kabel yoki quvvat bloki sifatsiz — kabelni almashtiring, USB hub o'rniga to'g'ridan-to'g'ri ulang.</p>"],
                    'ru' => ['На чеке иероглифы вместо текста',
                        'Не совпадает кодовая страница (codepage) или скорость COM. В утилите поставьте codepage CP866 / CP1251 (кириллица), для COM проверьте 9600 или 115200, драйвер — строго своей модели.',
                        "<h3>Иероглифы везде, даже вместо цифр</h3>
<p>Это <strong>скорость соединения</strong> (baud rate) или неверный драйвер.</p>
<ul><li>COM/последовательное подключение: Windows → Диспетчер устройств → COM-порт → <em>9600</em> должно совпадать со скоростью принтера (напечатана на тестовой странице).</li><li>USB: установлен драйвер другой модели — удалите и поставьте <a href=\"/support/article/install-windows-driver-receipt-printer\">драйвер своей модели</a>.</li><li>На этикеточный принтер поставлен чековый драйвер (или наоборот) — перепутаны TSPL и ESC/POS.</li></ul>
<h3>Латиница правильно, кириллица — нет</h3>
<p>Это <strong>кодовая страница</strong>. В утилите <em>Codepage</em> → <code>CP866</code> (большинство конфигураций 1С) или <code>CP1251</code> (Windows-1251). В программе выберите ту же кодовую страницу. Новые модели поддерживают и <code>UTF-8</code> — главное, чтобы совпадало с обеих сторон.</p>
<h3>Узбекские буквы (oʻ, gʻ, ʼ) неправильные</h3>
<p>В кириллических кодовых страницах их нет. Решение: программа должна отправлять чек <strong>в графическом режиме</strong> (в 1С — «Печать как картинка», Poster/iiko делают так сами) или UTF-8 на модели с его поддержкой.</p>
<h3>Несколько случайных символов посреди чека</h3>
<p>Плохой кабель или блок питания — замените кабель, подключите напрямую без USB-хаба.</p>"],
                    'en' => ['Garbage characters instead of text',
                        'The codepage does not match or the COM speed is wrong. Set codepage CP866 / CP1251 (Cyrillic) in the utility, check 9600 or 115200 on COM, and use the driver for your exact model.',
                        "<h3>Garbage everywhere, even digits</h3>
<p>That is <strong>baud rate</strong> or the wrong driver.</p>
<ul><li>COM/serial: Windows → Device Manager → COM port → <em>9600</em> must match the printer's speed (printed on the self-test page).</li><li>USB: a driver for another model is installed; remove it and install <a href=\"/support/article/install-windows-driver-receipt-printer\">your model's driver</a>.</li><li>A receipt driver on a label printer (or vice versa): TSPL and ESC/POS mixed up.</li></ul>
<h3>Latin is fine, Cyrillic is wrong</h3>
<p>That is the <strong>codepage</strong>. Utility <em>Codepage</em> → <code>CP866</code> (most 1C setups) or <code>CP1251</code> (Windows-1251). Pick the same codepage in the software. Newer models support <code>UTF-8</code> too; both sides must match.</p>
<h3>Uzbek letters (oʻ, gʻ, ʼ) wrong</h3>
<p>Cyrillic codepages don't contain them. Have the software send the receipt <strong>as graphics</strong> (1C: \"Print as image\", Poster/iiko do this by default) or use UTF-8 on a model that supports it.</p>
<h3>A few random symbols mid-receipt</h3>
<p>Bad cable or power supply: replace the cable and connect directly without a USB hub.</p>"],
                ],
            ],
            [
                'slug' => 'printer-not-detected-usb', 'type' => 'troubleshooting', 'models' => array_merge($receipt, $label),
                'keywords' => "kompyuter printerni ko'rmayapti, компьютер не видит принтер, usb не определяется, not detected, offline, принтер оффлайн, port usb001",
                't' => [
                    'uz' => ["Kompyuter printerni ko'rmayapti (USB) yoki printer \"Offline\"",
                        "Kabel va portni almashtirib ko'ring, printer yoqilgan va qopqoq yopilganini tekshiring, drayverda portni USB001 ga o'zgartiring. Qurilma menejerida \"USB Printing Support\" ko'rinishi kerak.",
                        "<ol>
<li><strong>Quvvat</strong>: printer yoqilgan (POWER yashil), qog'oz bor, qopqoq yopilgan. ERROR qizil yonsa — avval <a href=\"/support/article/red-error-light-blinking\">shu muammoni</a> hal qiling.</li>
<li><strong>Kabel va port</strong>: boshqa USB kabel va kompyuterning <em>orqa</em> USB portini sinang. USB hub va uzaytirgichlardan voz keching.</li>
<li><strong>Qurilma menejeri</strong> (Win+X → Device Manager): printerni ulaganda <em>USB Printing Support</em> yoki <em>USB Composite Device</em> paydo bo'lishi kerak. Sariq undov belgisi bo'lsa — drayverni qayta o'rnating. Hech narsa paydo bo'lmasa — kabel yoki printer platasi.</li>
<li><strong>Port noto'g'ri</strong>: Qurilmalar va printerlar → printer → Printer xususiyatlari → <em>Portlar</em>: <code>USB001</code> (yoki <code>USB002</code>…) belgilangan bo'lsin, <code>LPT1</code> emas. Bir nechta printer bo'lsa, ro'yxatdagi keyingi USB portni sinab ko'ring.</li>
<li><strong>Offline holati</strong>: printer oynasida <em>Printer → Use Printer Offline</em> belgisini olib tashlang; navbatdagi barcha vazifalarni o'chiring; <em>Print Spooler</em> xizmatini qayta ishga tushiring (services.msc).</li>
<li><strong>Drayverni qayta o'rnatish</strong>: eski printerni o'chiring, kabelni ajrating, drayverni o'rnating, o'rnatuvchi so'raganda kabelni ulang.</li>
</ol>
<p>Tarmoq printeri ko'rinmasa — <a href=\"/support/article/change-printer-ip-address\">IP manzilni tekshiring</a>: printer va kompyuter bir tarmoqda bo'lishi kerak, <code>ping</code> javob berishi kerak.</p>"],
                    'ru' => ['Компьютер не видит принтер (USB) или принтер «Оффлайн»',
                        'Поменяйте кабель и порт, проверьте, что принтер включён и крышка закрыта, в драйвере переключите порт на USB001. В диспетчере устройств должен появиться «USB Printing Support».',
                        "<ol>
<li><strong>Питание</strong>: принтер включён (POWER зелёный), бумага есть, крышка закрыта. Если горит красный ERROR — сначала решите <a href=\"/support/article/red-error-light-blinking\">эту проблему</a>.</li>
<li><strong>Кабель и порт</strong>: попробуйте другой USB-кабель и <em>задний</em> USB-порт компьютера. Откажитесь от хабов и удлинителей.</li>
<li><strong>Диспетчер устройств</strong> (Win+X → Диспетчер устройств): при подключении должен появиться <em>USB Printing Support</em> или <em>USB Composite Device</em>. Жёлтый восклицательный знак — переустановите драйвер. Ничего не появилось — кабель или плата принтера.</li>
<li><strong>Неверный порт</strong>: Устройства и принтеры → принтер → Свойства принтера → <em>Порты</em>: должен быть отмечен <code>USB001</code> (или <code>USB002</code>…), а не <code>LPT1</code>. Если принтеров несколько, попробуйте следующий USB-порт в списке.</li>
<li><strong>Состояние «Оффлайн»</strong>: в окне принтера снимите галочку <em>Принтер → Работать автономно</em>; очистите очередь печати; перезапустите службу <em>Диспетчер печати</em> (services.msc).</li>
<li><strong>Переустановка драйвера</strong>: удалите старый принтер, отключите кабель, установите драйвер, подключите кабель, когда попросит установщик.</li>
</ol>
<p>Если не виден сетевой принтер — <a href=\"/support/article/change-printer-ip-address\">проверьте IP</a>: принтер и компьютер должны быть в одной сети, <code>ping</code> должен отвечать.</p>"],
                    'en' => ['Computer does not detect the printer (USB) or printer shows Offline',
                        'Swap the cable and port, make sure the printer is on and the lid is closed, set the driver port to USB001. Device Manager should show "USB Printing Support".',
                        "<ol>
<li><strong>Power</strong>: printer on (POWER green), paper loaded, lid closed. If red ERROR is lit, fix <a href=\"/support/article/red-error-light-blinking\">that</a> first.</li>
<li><strong>Cable and port</strong>: try another USB cable and a <em>rear</em> USB port. Avoid hubs and extenders.</li>
<li><strong>Device Manager</strong> (Win+X → Device Manager): plugging in should add <em>USB Printing Support</em> or <em>USB Composite Device</em>. Yellow warning: reinstall the driver. Nothing appears: cable or printer board.</li>
<li><strong>Wrong port</strong>: Devices and Printers → printer → Printer properties → <em>Ports</em>: <code>USB001</code> (or <code>USB002</code>…) must be ticked, not <code>LPT1</code>. With several printers, try the next USB port in the list.</li>
<li><strong>Offline state</strong>: in the printer window untick <em>Printer → Use Printer Offline</em>; clear the queue; restart the <em>Print Spooler</em> service (services.msc).</li>
<li><strong>Reinstall</strong>: remove the old printer, unplug, install the driver, plug in when the installer asks.</li>
</ol>
<p>For a network printer that is not visible, <a href=\"/support/article/change-printer-ip-address\">check the IP</a>: printer and PC must be on the same subnet and <code>ping</code> must answer.</p>"],
                ],
            ],
            [
                'slug' => 'labels-skipping-or-misaligned', 'type' => 'troubleshooting', 'models' => $label,
                'keywords' => "etiket o'tkazib yuboradi, etiket siljiydi, пропускает этикетки, печатает со смещением, печатает между этикетками, label skipping, misaligned, bo'sh etiket",
                't' => [
                    'uz' => ["Etiketlar o'tkazib yuboriladi yoki siljib bosiladi",
                        "Uch sabab: kalibrovka qilinmagan, drayverdagi etiket o'lchami rulonga mos emas, sensor turi noto'g'ri (gap / black mark). Tartib bilan: o'lcham → sensor → kalibrovka.",
                        "<ol>
<li><strong>Rulonni o'lchang</strong>: etiket kengligi × uzunligi (mm) va etiketlar orasidagi oraliq (odatda 2–3 mm). Masalan, 58×40, oraliq 3.</li>
<li><strong>Drayverda o'lchamni to'g'rilang</strong>: Printing Preferences → Page Setup → o'sha o'lcham. Dastur shablonida ham (Uzum PDF, 1C, dizayner) — bir xil. <a href=\"/support/article/install-windows-driver-label-printer\">Batafsil</a>.</li>
<li><strong>Sensor turi</strong>: Stock → <em>Labels with gaps</em> (oq oraliqli), <em>Black mark</em> (orqasida qora chiziq), <em>Continuous</em> (uzluksiz). Noto'g'ri sensor — printer har safar \"adashadi\".</li>
<li><strong>Kalibrovka</strong>: FEED ni 2–3 soniya bosib turing. <a href=\"/support/article/label-printer-calibration\">Qanday</a>.</li>
<li><strong>Rulon joylashuvi</strong>: yon tomondagi plastik yo'naltiruvchilar rulonni zich ushlasin — qiyshiq ketmasin.</li>
</ol>
<h3>Har ikkinchi etiket bo'sh</h3>
<p>Dastur hujjatni 2 sahifa qilib yuboradi (shablon etiketdan uzunroq). Shablon uzunligini etiketdan 1–2 mm <em>kichik</em> qiling.</p>
<h3>Bosma yuqori/pastga surilgan</h3>
<p>Drayverda <em>Vertical offset</em> / <em>Shift</em> (yoki TSPL <code>OFFSET</code>) ni ±1–3 mm o'zgartiring.</p>"],
                    'ru' => ['Этикетки пропускаются или печатаются со смещением',
                        'Три причины: не сделана калибровка, размер этикетки в драйвере не совпадает с рулоном, неверный тип датчика (gap / black mark). По порядку: размер → датчик → калибровка.',
                        "<ol>
<li><strong>Измерьте рулон</strong>: ширина × длина этикетки (мм) и зазор между этикетками (обычно 2–3 мм). Например, 58×40, зазор 3.</li>
<li><strong>Исправьте размер в драйвере</strong>: Printing Preferences → Page Setup → этот размер. В шаблоне программы (PDF Uzum, 1С, дизайнер) — такой же. <a href=\"/support/article/install-windows-driver-label-printer\">Подробнее</a>.</li>
<li><strong>Тип датчика</strong>: Stock → <em>Labels with gaps</em> (белые с зазором), <em>Black mark</em> (чёрная метка сзади), <em>Continuous</em> (сплошная). Неверный датчик — принтер «теряется» каждый раз.</li>
<li><strong>Калибровка</strong>: удерживайте FEED 2–3 секунды. <a href=\"/support/article/label-printer-calibration\">Как</a>.</li>
<li><strong>Установка рулона</strong>: боковые пластиковые направляющие должны плотно держать рулон, чтобы он не уходил вбок.</li>
</ol>
<h3>Каждая вторая этикетка пустая</h3>
<p>Программа отправляет документ на 2 страницы (шаблон длиннее этикетки). Сделайте шаблон на 1–2 мм <em>короче</em> этикетки.</p>
<h3>Печать сдвинута вверх/вниз</h3>
<p>В драйвере измените <em>Vertical offset</em> / <em>Shift</em> (или TSPL <code>OFFSET</code>) на ±1–3 мм.</p>"],
                    'en' => ['Labels are skipped or printed out of alignment',
                        'Three causes: no calibration, driver label size not matching the roll, wrong sensor type (gap / black mark). In order: size → sensor → calibrate.',
                        "<ol>
<li><strong>Measure the roll</strong>: label width × height (mm) and the gap between labels (usually 2–3 mm). E.g. 58×40, gap 3.</li>
<li><strong>Fix the driver size</strong>: Printing Preferences → Page Setup → that size. Same in the software template (Uzum PDF, 1C, designer). <a href=\"/support/article/install-windows-driver-label-printer\">Details</a>.</li>
<li><strong>Sensor type</strong>: Stock → <em>Labels with gaps</em> (white with gaps), <em>Black mark</em> (black line on the back), <em>Continuous</em>. Wrong sensor and the printer loses position every time.</li>
<li><strong>Calibrate</strong>: hold FEED for 2–3 seconds. <a href=\"/support/article/label-printer-calibration\">How</a>.</li>
<li><strong>Roll seating</strong>: the side guides must hold the roll snugly so it doesn't wander.</li>
</ol>
<h3>Every second label blank</h3>
<p>The software sends a 2-page document (template longer than the label). Make the template 1–2 mm <em>shorter</em> than the label.</p>
<h3>Print shifted up/down</h3>
<p>Adjust <em>Vertical offset</em> / <em>Shift</em> in the driver (or TSPL <code>OFFSET</code>) by ±1–3 mm.</p>"],
                ],
            ],
            [
                'slug' => 'red-error-light-blinking', 'type' => 'troubleshooting', 'models' => $receipt,
                'keywords' => "qizil chiroq, error yonadi, красная лампочка мигает, горит error, red light blinking, paper out, cover open, qog'oz tugadi, перегрев",
                't' => [
                    'uz' => ["Qizil ERROR indikatori yonadi yoki miltillaydi",
                        "Qizil chiroq = printer o'zi muammoni ko'rsatmoqda: qog'oz tugagan, qopqoq ochiq, kesgich tiqilib qolgan yoki bosh qizib ketgan. Miltillash soni sababni aytadi.",
                        "<table><thead><tr><th>Indikator</th><th>Sabab</th><th>Yechim</th></tr></thead><tbody>
<tr><td>Qizil doimiy yonadi, PAPER ham</td><td>Qog'oz tugagan yoki sensor qog'ozni ko'rmaydi</td><td>Yangi rulon qo'ying, rulon sensor ustida turganiga ishonch hosil qiling</td></tr>
<tr><td>Qizil doimiy</td><td>Qopqoq ochiq</td><td>Qopqoqni ikki tomondan \"shchelk\"gacha bosing</td></tr>
<tr><td>Qizil tez miltillaydi</td><td>Kesgich tiqilib qolgan (cutter jam)</td><td>Printerni o'chiring, qopqoqni oching, kesgich pichog'ini qo'lda joyiga qaytaring (ko'p modellarda — kesgich qopqog'i ostidagi tishli g'ildirakni aylantiring), qog'oz parchalarini olib tashlang</td></tr>
<tr><td>Qizil sekin miltillaydi</td><td>Bosh qizib ketgan</td><td>5–10 daqiqa o'chirib qo'ying; uzluksiz katta hajmda bosishni kamaytiring</td></tr>
<tr><td>Qizil + yashil birga</td><td>Quvvat bloki yetarli kuchlanish bermayapti</td><td>Asl 24V adapterni ishlating; uzaytirgich/UPS ni tekshiring</td></tr>
</tbody></table>
<p>Hamma indikatorlar yonib, printer javob bermasa — platada muammo. <a href=\"/partners?type=service\">Servis markazi</a>, kafolat 12 oy.</p>"],
                    'ru' => ['Горит или мигает красный индикатор ERROR',
                        'Красный свет = принтер сам сообщает о проблеме: кончилась бумага, открыта крышка, заклинил резак или перегрелась головка. Характер мигания подсказывает причину.',
                        "<table><thead><tr><th>Индикатор</th><th>Причина</th><th>Решение</th></tr></thead><tbody>
<tr><td>Красный горит постоянно, PAPER тоже</td><td>Кончилась бумага или датчик её не видит</td><td>Поставьте новый рулон, убедитесь, что рулон лежит над датчиком</td></tr>
<tr><td>Красный постоянно</td><td>Открыта крышка</td><td>Прижмите крышку с двух сторон до щелчка</td></tr>
<tr><td>Красный быстро мигает</td><td>Заклинил резак (cutter jam)</td><td>Выключите принтер, откройте крышку, вручную верните нож (на многих моделях — покрутите шестерёнку под крышкой резака), удалите обрывки бумаги</td></tr>
<tr><td>Красный медленно мигает</td><td>Перегрев головки</td><td>Выключите на 5–10 минут; уменьшите непрерывную печать больших объёмов</td></tr>
<tr><td>Красный + зелёный вместе</td><td>Блок питания не даёт нужное напряжение</td><td>Используйте оригинальный адаптер 24V; проверьте удлинитель/UPS</td></tr>
</tbody></table>
<p>Если горят все индикаторы и принтер не отвечает — проблема с платой. <a href=\"/partners?type=service\">Сервис-центр</a>, гарантия 12 месяцев.</p>"],
                    'en' => ['Red ERROR light on or blinking',
                        'A red light means the printer is reporting a problem: out of paper, lid open, cutter jammed or head overheated. The blink pattern tells you which.',
                        "<table><thead><tr><th>Indicator</th><th>Cause</th><th>Fix</th></tr></thead><tbody>
<tr><td>Red solid, PAPER also lit</td><td>Out of paper or sensor can't see it</td><td>Load a new roll, make sure it sits over the sensor</td></tr>
<tr><td>Red solid</td><td>Lid open</td><td>Press the lid down on both sides until it clicks</td></tr>
<tr><td>Red fast blink</td><td>Cutter jam</td><td>Power off, open the lid, return the blade by hand (many models: turn the gear under the cutter cover), remove paper scraps</td></tr>
<tr><td>Red slow blink</td><td>Head overheated</td><td>Power off for 5–10 minutes; reduce long continuous print runs</td></tr>
<tr><td>Red + green together</td><td>Power supply under voltage</td><td>Use the original 24V adapter; check the power strip/UPS</td></tr>
</tbody></table>
<p>All lights on and no response means a board fault. <a href=\"/partners?type=service\">Service center</a>, 12-month warranty.</p>"],
                ],
            ],

            // ── FAQ ────────────────────────────────────────────
            [
                'slug' => 'warranty-and-service-how-it-works', 'type' => 'faq',
                'keywords' => "kafolat, гарантия 12 месяцев, warranty, servis markazi, сервис, ta'mirlash, ремонт, serial number check",
                't' => [
                    'uz' => ["Kafolat va servis qanday ishlaydi",
                        "Platformadagi tekshirilgan dilerlardan olingan Xprinter printerlariga O'zbekistonda 12 oy kafolat. Ta'mir — tarmoqdagi servis markazlarida, seriya raqami bo'yicha.",
                        "<h3>Kafolat</h3>
<ul><li>Muddat: <strong>12 oy</strong> sotib olingan kundan.</li><li>Kafolat ostida: zavod nuqsonlari — plata, bosh (mexanik shikastsiz), quvvat bloki, kesgich mexanizmi.</li><li>Kafolat ostida emas: tushib ketish, suyuqlik, noto'g'ri quvvat bloki, boshqa modelning firmware'i, yelim/chang tufayli bosh yeyilishi, sifatsiz qog'oz.</li></ul>
<h3>Qanday murojaat qilish</h3>
<ol><li>Seriya raqamini (printer tagidagi stiker) va sotib olgan diler nomini tayyorlang.</li><li><a href=\"/partners?type=service\">Servis markazlari</a> ro'yxatidan yaqinini tanlang, qo'ng'iroq qiling.</li><li>Printerni quvvat bloki bilan olib boring. Diagnostika odatda 1–3 ish kuni.</li></ol>
<h3>Oldin o'zingiz tekshiring</h3>
<p>Ko'p \"nosozliklar\" sozlama: <a href=\"/support/article/printer-prints-blank-receipt\">bo'sh chek</a>, <a href=\"/support/article/garbage-characters-instead-of-text\">iyerogliflar</a>, <a href=\"/support/article/printer-not-detected-usb\">ko'rinmaydi</a>. Bu 5 daqiqalik yechimlar.</p>"],
                    'ru' => ['Как работает гарантия и сервис',
                        'На принтеры Xprinter, купленные у проверенных дилеров платформы, действует гарантия 12 месяцев в Узбекистане. Ремонт — в сервис-центрах сети, по серийному номеру.',
                        "<h3>Гарантия</h3>
<ul><li>Срок: <strong>12 месяцев</strong> с даты покупки.</li><li>Покрывается: заводские дефекты — плата, головка (без механических повреждений), блок питания, механизм резака.</li><li>Не покрывается: падение, жидкость, неоригинальный блок питания, прошивка от другой модели, износ головки из-за клея/пыли, некачественная бумага.</li></ul>
<h3>Как обратиться</h3>
<ol><li>Подготовьте серийный номер (наклейка на дне принтера) и название дилера, где купили.</li><li>Выберите ближайший из <a href=\"/partners?type=service\">сервис-центров</a>, позвоните.</li><li>Привезите принтер с блоком питания. Диагностика обычно 1–3 рабочих дня.</li></ol>
<h3>Сначала проверьте сами</h3>
<p>Многие «поломки» — настройки: <a href=\"/support/article/printer-prints-blank-receipt\">пустой чек</a>, <a href=\"/support/article/garbage-characters-instead-of-text\">иероглифы</a>, <a href=\"/support/article/printer-not-detected-usb\">не виден</a>. Это 5-минутные решения.</p>"],
                    'en' => ['How warranty and service work',
                        'Xprinter printers bought from verified dealers on the platform carry a 12-month warranty in Uzbekistan. Repairs happen at network service centers, by serial number.',
                        "<h3>Warranty</h3>
<ul><li>Term: <strong>12 months</strong> from purchase.</li><li>Covered: factory defects in the board, print head (without mechanical damage), power supply, cutter mechanism.</li><li>Not covered: drops, liquids, non-original power supply, firmware from another model, head wear from adhesive/dust, poor paper.</li></ul>
<h3>How to claim</h3>
<ol><li>Have the serial number (sticker on the bottom) and the dealer's name ready.</li><li>Pick the nearest <a href=\"/partners?type=service\">service center</a> and call.</li><li>Bring the printer with its power supply. Diagnosis usually takes 1–3 working days.</li></ol>
<h3>Check yourself first</h3>
<p>Many \"failures\" are settings: <a href=\"/support/article/printer-prints-blank-receipt\">blank receipt</a>, <a href=\"/support/article/garbage-characters-instead-of-text\">garbage characters</a>, <a href=\"/support/article/printer-not-detected-usb\">not detected</a>. Five-minute fixes.</p>"],
                ],
            ],
        ];
    }
}
