<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UiTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TranslationController extends Controller
{
    public function index(): View
    {
        $rows = UiTranslation::orderBy('group')->orderBy('key')->get();

        $groups = [];
        foreach ($rows as $row) {
            $groups[$row->group][$row->key][$row->lang] = $row->value;
        }

        return view('admin.translations.index', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $group = $request->input('group');
        $keys  = $request->input('t', []);

        foreach ($keys as $key => $langs) {
            foreach ($langs as $lang => $value) {
                UiTranslation::updateOrCreate(
                    ['group' => $group, 'key' => $key, 'lang' => $lang],
                    ['value' => trim($value)]
                );
            }
        }

        Cache::forget('ui_translations');

        return back()->with('success', "«{$group}» guruhi saqlandi");
    }

    public function addKey(Request $request): RedirectResponse
    {
        $request->validate([
            'group'    => 'required|string|max:50|regex:/^[a-z_]+$/',
            'key'      => 'required|string|max:100|regex:/^[a-z_.]+$/',
            'value_uz' => 'required|string|max:500',
            'value_ru' => 'required|string|max:500',
        ]);

        foreach (['uz', 'ru'] as $lang) {
            UiTranslation::updateOrCreate(
                ['group' => $request->group, 'key' => $request->key, 'lang' => $lang],
                ['value' => trim($request->{"value_{$lang}"})]
            );
        }

        Cache::forget('ui_translations');

        return back()->with('success', "{$request->group}.{$request->key} kaliti qo'shildi");
    }

    public function destroyKey(Request $request): RedirectResponse
    {
        $request->validate([
            'group' => 'required|string|max:50',
            'key'   => 'required|string|max:100',
        ]);

        UiTranslation::where('group', $request->group)
                     ->where('key', $request->key)
                     ->delete();

        Cache::forget('ui_translations');

        return back()->with('success', "Kalit o'chirildi");
    }
}
