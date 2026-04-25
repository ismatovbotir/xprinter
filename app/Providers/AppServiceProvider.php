<?php

namespace App\Providers;

use App\Models\UiTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadUiTranslations();
    }

    private function loadUiTranslations(): void
    {
        try {
            $rows = Cache::remember('ui_translations', 3600, fn() => UiTranslation::all());
        } catch (\Throwable) {
            return;
        }

        $byLang = $rows->groupBy('lang');

        foreach ($byLang as $lang => $items) {
            $lines = [];
            foreach ($items as $item) {
                $lines["{$item->group}.{$item->key}"] = $item->value;
            }
            app('translator')->addLines($lines, $lang);
        }
    }
}
