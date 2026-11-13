<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\KbArticle;
use App\Models\Product;
use App\Services\Chatbot\ChatbotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\MakesCompanies;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use RefreshDatabase, MakesCompanies;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\UiTranslationSeeder::class);
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);
    }

    private function article(array $attrs = [], array $t = []): KbArticle
    {
        static $n = 0;
        $n++;

        $a = KbArticle::create($attrs + [
            'slug' => "article-{$n}", 'type' => 'troubleshooting', 'is_published' => true,
        ]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $a->translations()->create($t[$lang] ?? [
                'lang' => $lang, 'title' => "Title {$lang} {$n}", 'excerpt' => "Excerpt {$lang} {$n}", 'body' => "<p>Body {$lang} {$n}</p>",
            ]);
        }

        return $a;
    }

    public function test_support_index_shows_type_tiles_and_popular_articles(): void
    {
        $this->article([], ['uz' => ['lang' => 'uz', 'title' => "Bo'sh chek chiqadi", 'excerpt' => 'Qog\'ozni aylantiring', 'body' => '<p>x</p>']]);
        $this->article(['is_published' => false], ['uz' => ['lang' => 'uz', 'title' => 'Draft only', 'excerpt' => null, 'body' => '<p>x</p>']]);

        $this->get(route('support.index'))
            ->assertOk()
            ->assertSee("Bo'sh chek chiqadi")
            ->assertDontSee('Draft only')
            ->assertSee(route('support.type', 'troubleshooting'));
    }

    public function test_search_finds_by_keywords_in_any_language(): void
    {
        $this->article(['keywords' => 'пустой чек, blank print'], ['uz' => ['lang' => 'uz', 'title' => "Bo'sh chek", 'excerpt' => null, 'body' => '<p>x</p>']]);
        $this->article([], ['uz' => ['lang' => 'uz', 'title' => 'IP manzil', 'excerpt' => null, 'body' => '<p>x</p>']]);

        $this->get(route('support.index', ['q' => 'пустой чек']))
            ->assertOk()
            ->assertSee("Bo'sh chek")
            ->assertDontSee('IP manzil');
    }

    public function test_article_page_renders_in_current_locale_and_counts_views(): void
    {
        $a = $this->article([], [
            'uz' => ['lang' => 'uz', 'title' => 'UZ sarlavha', 'excerpt' => null, 'body' => '<p>UZ matn</p><script>alert(1)</script>'],
            'ru' => ['lang' => 'ru', 'title' => 'RU заголовок', 'excerpt' => null, 'body' => '<p>RU текст</p>'],
        ]);

        $this->get(route('support.show', $a->slug))
            ->assertOk()
            ->assertSee('UZ sarlavha')
            ->assertSee('UZ matn')
            ->assertDontSee('alert(1)', false); // script tags are stripped from article bodies

        $this->withSession(['lang' => 'ru'])->get(route('support.show', $a->slug))
            ->assertOk()
            ->assertSee('RU заголовок');

        $this->assertSame(2, $a->fresh()->views_count);
    }

    public function test_unpublished_article_and_unknown_type_are_404(): void
    {
        $a = $this->article(['is_published' => false]);

        $this->get(route('support.show', $a->slug))->assertNotFound();
        $this->get('/support/nonsense-type')->assertNotFound();
        $this->get(route('support.type', 'glossary'))->assertOk();
    }

    public function test_product_page_lists_articles_for_model_or_category(): void
    {
        $category = Category::create(['slug' => 'receipt-printers']);
        $product  = Product::create(['category_id' => $category->id, 'slug' => 'xp-q890k', 'model_number' => 'XP-Q890K']);
        $other    = Product::create(['category_id' => Category::create(['slug' => 'label-printers'])->id, 'slug' => 'xp-420b', 'model_number' => 'XP-420B']);

        $byModel    = $this->article([], ['uz' => ['lang' => 'uz', 'title' => 'Model maqola', 'excerpt' => null, 'body' => '<p>x</p>']]);
        $byModel->products()->attach($product->id);
        $this->article(['category_id' => $category->id], ['uz' => ['lang' => 'uz', 'title' => 'Kategoriya maqola', 'excerpt' => null, 'body' => '<p>x</p>']]);
        $unrelated  = $this->article([], ['uz' => ['lang' => 'uz', 'title' => 'Etiket maqola', 'excerpt' => null, 'body' => '<p>x</p>']]);
        $unrelated->products()->attach($other->id);

        $this->get(route('products.show', ['receipt-printers', 'xp-q890k']))
            ->assertOk()
            ->assertSee('Model maqola')
            ->assertSee('Kategoriya maqola')
            ->assertDontSee('Etiket maqola');

        $this->get(route('support.type', ['type' => 'troubleshooting', 'product' => 'xp-q890k']))
            ->assertOk()
            ->assertSee('Model maqola')
            ->assertDontSee('Etiket maqola');
    }

    public function test_admin_creates_article_with_three_translations_and_products(): void
    {
        $admin   = $this->makeAdmin();
        $product = $this->makeProduct();

        $this->actingAs($admin)->post(route('admin.kb.store'), [
            'type' => 'settings', 'difficulty' => 'medium', 'keywords' => 'ip, адрес', 'is_published' => 1, 'sort_order' => 3,
            'products' => [$product->id],
            'title_uz' => 'IP manzil', 'body_uz' => '<p>uz</p>',
            'title_ru' => 'IP адрес',  'body_ru' => '<p>ru</p>',
            'title_en' => 'Change IP address', 'body_en' => '<p>en</p>', 'excerpt_en' => 'Short',
        ])->assertRedirect(route('admin.kb.index'));

        $a = KbArticle::where('slug', 'change-ip-address')->firstOrFail();
        $this->assertSame(3, $a->translations()->count());
        $this->assertTrue($a->is_published);
        $this->assertSame([$product->id], $a->products->pluck('id')->all());

        $this->actingAs($admin)->get(route('admin.kb.index'))->assertOk()->assertSee('IP manzil');
        $this->actingAs($this->makeOwnerWithCompany())->get(route('admin.kb.index'))->assertForbidden();
    }

    public function test_admin_validation_requires_all_languages(): void
    {
        $this->actingAs($this->makeAdmin())->post(route('admin.kb.store'), [
            'type' => 'faq', 'difficulty' => 'easy', 'title_uz' => 'x', 'body_uz' => 'x',
        ])->assertSessionHasErrors(['title_ru', 'body_ru', 'title_en', 'body_en']);
    }

    public function test_chatbot_answers_from_knowledge_base(): void
    {
        $this->article(['keywords' => "bo'sh chek, oq chek, пустой чек, белый чек"], [
            'uz' => ['lang' => 'uz', 'title' => "Printer bo'sh chek chiqaradi", 'excerpt' => "Qog'ozni aylantiring — termo qatlam yuqoriga.", 'body' => '<p>x</p>'],
            'ru' => ['lang' => 'ru', 'title' => 'Принтер печатает пустой чек', 'excerpt' => 'Переверните рулон — термослой вверх.', 'body' => '<p>x</p>'],
        ]);

        $uz = app(ChatbotService::class)->respond("printer bo'sh chek chiqaryapti nima qilay", 'web');
        $this->assertStringContainsString("Qog'ozni aylantiring", $uz->text);
        $this->assertStringContainsString('/support/article/', $uz->text);

        $ru = app(ChatbotService::class)->respond('принтер печатает пустой чек', 'web');
        $this->assertStringContainsString('Переверните рулон', $ru->text);
    }

    public function test_chatbot_answers_definition_questions_from_glossary(): void
    {
        $this->article(['type' => 'glossary', 'keywords' => 'firmware nima, прошивка'], [
            'uz' => ['lang' => 'uz', 'title' => 'Firmware (proshivka) nima?', 'excerpt' => 'Firmware — printer ichidagi dastur.', 'body' => '<p>x</p>'],
            'ru' => ['lang' => 'ru', 'title' => 'Что такое прошивка (firmware)?', 'excerpt' => 'Прошивка — программа внутри принтера.', 'body' => '<p>x</p>'],
        ]);

        $uz = app(ChatbotService::class)->respond('firmware nima', 'web');
        $this->assertStringContainsString('printer ichidagi dastur', $uz->text);

        $ru = app(ChatbotService::class)->respond('что такое прошивка', 'web');
        $this->assertStringContainsString('программа внутри принтера', $ru->text);
    }

    public function test_seeder_loads_starter_knowledge(): void
    {
        $this->seed(\Database\Seeders\KbSeeder::class);

        $this->assertGreaterThanOrEqual(15, KbArticle::published()->count());
        $this->assertSame(0, KbArticle::doesntHave('translations')->count());
        $this->get(route('support.show', 'what-is-a-printer-driver'))->assertOk()->assertSee('Drayver');
        $this->get(route('support.type', 'glossary'))->assertOk();
    }
}
