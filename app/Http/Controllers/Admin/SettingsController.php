<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $settings = SiteSetting::current();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'phone'                => 'nullable|string|max:50',
            'telegram'             => 'nullable|string|max:100',
            'whatsapp'             => 'nullable|string|max:50',
            'email'                => 'nullable|email|max:255',
            'address'              => 'nullable|string|max:500',
            'work_days'            => 'nullable|array',
            'work_days.*'          => 'in:' . implode(',', array_keys(SiteSetting::WEEK_DAYS)),
            'work_time_from'       => 'nullable|string|max:10',
            'work_time_to'         => 'nullable|string|max:10',
            'google_analytics_id'  => 'nullable|string|max:50',
            'yandex_metrica_id'    => 'nullable|string|max:50',
        ]);

        SiteSetting::current()->update([
            ...$request->only([
                'phone', 'telegram', 'whatsapp', 'email', 'address',
                'work_time_from', 'work_time_to',
                'google_analytics_id', 'yandex_metrica_id',
            ]),
            'work_days' => $request->input('work_days', []),
        ]);

        SiteSetting::forgetCache();

        return redirect()->route('admin.settings.edit')
            ->with('success', "Sozlamalar saqlandi");
    }
}
