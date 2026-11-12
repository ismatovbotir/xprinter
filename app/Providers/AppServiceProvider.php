<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\UiTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configurePasswordPolicy();
        $this->loadUiTranslations();
        $this->shareSiteSettings();
    }

    /** One password policy for register, team invites, admin-created users and resets. */
    private function configurePasswordPolicy(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)->letters()->numbers();

            return $this->app->isProduction()
                ? $rule->uncompromised()
                : $rule;
        });
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

    private function shareSiteSettings(): void
    {
        // Resolved lazily on first render (not at boot), so it also works when the
        // database isn't ready yet at boot time — fresh installs, migrations, tests.
        View::composer('*', function ($view) {
            static $settings;

            try {
                $settings ??= SiteSetting::current();
            } catch (\Throwable) {
                return;
            }

            $view->with('siteSettings', $settings);
        });
    }
}
