<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Models\HomeContent;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeContentController extends Controller
{
    public function edit(): View
    {
        $content = HomeContent::current();

        $products = Product::with(['translations', 'category.translations'])
            ->orderBy('category_id')->orderBy('model_number')->get()
            ->groupBy(fn($p) => $p->category?->translations->firstWhere('lang', 'uz')?->name ?? 'Boshqa');

        return view('admin.homepage.edit', compact('content', 'products'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'hero_tag'         => 'nullable|string|max:100',
            'hero_line1'       => 'nullable|string|max:100',
            'hero_line2'       => 'nullable|string|max:100',
            'hero_line3'       => 'nullable|string|max:100',
            'hero_subtitle'    => 'nullable|string|max:500',
            'badges'           => 'nullable|array',
            'badges.*'         => 'nullable|string|max:100',
            'stat_values'      => 'nullable|array',
            'stat_values.*'    => 'nullable|string|max:20',
            'stat_suffixes'    => 'nullable|array',
            'stat_suffixes.*'  => 'nullable|string|max:10',
            'stat_labels'      => 'nullable|array',
            'stat_labels.*'    => 'nullable|string|max:100',
            'about_tag'        => 'nullable|string|max:100',
            'about_title'      => 'nullable|string|max:150',
            'about_subtitle'   => 'nullable|string|max:500',
            'card_titles'      => 'nullable|array',
            'card_titles.*'    => 'nullable|string|max:100',
            'card_texts'       => 'nullable|array',
            'card_texts.*'     => 'nullable|string|max:300',
            'hero_products'    => 'nullable|array|max:' . HomeContent::HERO_MAX,
            'hero_products.*'  => 'nullable|integer|exists:products,id',
            'scenario_products'     => 'nullable|array',
            'scenario_products.*'   => 'nullable|array|max:' . HomeContent::SCENARIO_MAX,
            'scenario_products.*.*' => 'nullable|integer|exists:products,id',
        ]);

        $ids = fn($list) => collect($list)->filter()->map(fn($id) => (int) $id)->unique()->values()->all();

        $scenarios = [];
        foreach (array_keys(HomeContent::SCENARIOS) as $key) {
            $scenarios[$key] = $ids($request->input("scenario_products.$key", []));
        }

        $stats = [];
        foreach ($request->input('stat_values', []) as $i => $value) {
            $stats[] = [
                'value'  => $value,
                'suffix' => $request->input("stat_suffixes.$i", ''),
                'label'  => $request->input("stat_labels.$i", ''),
            ];
        }

        $aboutCards = [];
        foreach ($request->input('card_titles', []) as $i => $title) {
            $aboutCards[] = [
                'title' => $title,
                'text'  => $request->input("card_texts.$i", ''),
            ];
        }

        HomeContent::current()->update([
            'hero_tag'       => $request->hero_tag,
            'hero_line1'     => $request->hero_line1,
            'hero_line2'     => $request->hero_line2,
            'hero_line3'     => $request->hero_line3,
            'hero_subtitle'  => $request->hero_subtitle,
            'hero_badges'    => array_values(array_filter($request->input('badges', []))),
            'stats'          => $stats,
            'about_tag'      => $request->about_tag,
            'about_title'    => $request->about_title,
            'about_subtitle' => $request->about_subtitle,
            'about_cards'    => $aboutCards,
            'hero_products'     => $ids($request->input('hero_products', [])),
            'scenario_products' => $scenarios,
        ]);

        HomeContent::forgetCache();
        HomeController::forgetShowcase();

        return redirect()->route('admin.homepage.edit')
            ->with('success', 'Bosh sahifa tarkibi saqlandi');
    }
}
