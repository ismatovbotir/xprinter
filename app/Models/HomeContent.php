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
    ];

    protected $casts = [
        'hero_badges' => 'array',
        'stats'       => 'array',
        'about_cards' => 'array',
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
}
