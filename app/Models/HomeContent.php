<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HomeContent extends Model
{
    protected $fillable = [
        'hero_tag',
        'hero_line1',
        'hero_line2',
        'hero_line3',
        'hero_subtitle',
        'hero_badges',
        'stats',
        'about_tag',
        'about_title',
        'about_subtitle',
        'about_cards',
        'hero_products',
        'scenario_products',
    ];

    /** Use-case cards on the home page (key → translation key of the title). */
    public const SCENARIOS = [
        'shop'        => 'home.scenario_shop',
        'cafe'        => 'home.scenario_cafe',
        'pharmacy'    => 'home.scenario_pharmacy',
        'marketplace' => 'home.scenario_marketplace',
        'medical'     => 'home.scenario_medical',
        'courier'     => 'home.scenario_courier',
    ];

    public const HERO_MAX = 8;
    public const SCENARIO_MAX = 3;

    /** Used until the admin picks products (model numbers; missing ones are skipped). */
    private const DEFAULT_HERO = ['XP-365B', 'XP-T80A', 'XP-K200L', 'XP-470B', 'XP-P502A', 'XP-H400E'];
    private const DEFAULT_SCENARIOS = [
        'shop'        => ['XP-T80A', 'XP-365B'],
        'cafe'        => ['XP-58IIT', 'XP-T80Q'],
        'pharmacy'    => ['XP-Q80A', 'XP-365B'],
        'marketplace' => ['XP-365B', 'XP-421B', 'XP-470B'],
        'medical'     => ['XP-58IIA', 'XP-370B'],
        'courier'     => ['XP-P502A', 'XP-P801A', 'XP-P326B'],
    ];

    protected $casts = [
        'hero_badges' => 'array',
        'stats'       => 'array',
        'about_cards' => 'array',
        'hero_products'     => 'array',
        'scenario_products' => 'array',
    ];

    public static function current(): self
    {
        return Cache::rememberForever('home_content', fn() => static::query()->firstOrCreate(['id' => 1]));
    }

    public static function forgetCache(): void
    {
        Cache::forget('home_content');
    }

    public function badges(): array
    {
        $badges = $this->hero_badges ?: [];

        return array_pad(array_slice($badges, 0, 3), 3, '');
    }

    public function statList(): array
    {
        $stats = $this->stats ?: [];

        return array_pad(array_slice($stats, 0, 4), 4, ['value' => '', 'suffix' => '', 'label' => '']);
    }

    public function aboutCardList(): array
    {
        $cards = $this->about_cards ?: [];

        return array_pad(array_slice($cards, 0, 3), 3, ['title' => '', 'text' => '']);
    }

    /** Hero carousel product ids, in the admin's order. */
    public function heroProductIds(): array
    {
        return $this->hero_products
            ? array_map('intval', $this->hero_products)
            : $this->idsForModels(self::DEFAULT_HERO);
    }

    /** @return array<string, int[]> scenario key → product ids */
    public function scenarioProductIds(): array
    {
        $saved = $this->scenario_products ?? [];

        return collect(self::SCENARIOS)->map(fn ($_, $key) => isset($saved[$key])
            ? array_map('intval', $saved[$key])
            : $this->idsForModels(self::DEFAULT_SCENARIOS[$key] ?? [])
        )->all();
    }

    private function idsForModels(array $models): array
    {
        $ids = Product::whereIn('model_number', $models)->pluck('id', 'model_number');

        return collect($models)->map(fn ($m) => $ids[$m] ?? null)->filter()->values()->all();
    }
}
