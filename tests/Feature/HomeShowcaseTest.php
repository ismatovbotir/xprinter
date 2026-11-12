<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\HomeContent;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class HomeShowcaseTest extends TestCase
{
    use RefreshDatabase;

    private function product(string $model, Category $cat): Product
    {
        $p = Product::create(['category_id' => $cat->id, 'slug' => strtolower($model), 'model_number' => $model]);
        $p->translations()->create(['lang' => 'uz', 'name' => "Printer {$model}", 'description' => '']);

        return $p;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_defaults_are_used_until_admin_picks(): void
    {
        $cat = Category::create(['slug' => 'label-printers']);
        $cat->translations()->create(['lang' => 'uz', 'name' => 'Etiketka printerlar']);
        $this->product('XP-365B', $cat);

        $this->get('/')->assertOk()
            ->assertSee('data-hero-carousel', false)
            ->assertSee('XP-365B');
    }

    public function test_admin_picks_hero_and_scenario_products(): void
    {
        $cat = Category::create(['slug' => 'receipt-printers']);
        $a = $this->product('XP-T80A', $cat);
        $b = $this->product('XP-K200L', $cat);
        $c = $this->product('XP-58IIT', $cat);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.homepage.edit'))->assertOk()->assertSee('Hero karusel');

        $this->actingAs($admin)->put(route('admin.homepage.update'), [
            'hero_products'     => [$b->id, '', $a->id, $b->id],
            'scenario_products' => ['cafe' => [$c->id, '', '']],
        ])->assertRedirect(route('admin.homepage.edit'));

        $content = HomeContent::query()->find(1);
        $this->assertSame([$b->id, $a->id], $content->hero_products);
        $this->assertSame([$c->id], $content->scenario_products['cafe']);
        $this->assertSame([], $content->scenario_products['shop']);

        $html = $this->get('/')->assertOk()->getContent();
        $this->assertLessThan(strpos($html, 'aria-label="2 / 2: XP-T80A"'), strpos($html, 'aria-label="1 / 2: XP-K200L"'));
        $this->assertStringContainsString('XP-58IIT</a>', $html);
    }

    public function test_rejects_unknown_product_ids(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->put(route('admin.homepage.update'), ['hero_products' => [999]])
            ->assertSessionHasErrors('hero_products.0');
    }
}
