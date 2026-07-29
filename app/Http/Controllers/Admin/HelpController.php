<?php

namespace App\Http\Controllers\Admin;

use App\Models\HelpArticle;
use App\Http\Controllers\Concerns\GeneratesSlug;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    use GeneratesSlug;


    public function index()
    {
        $articles = HelpArticle::with('translations')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.help.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.help.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|in:marketplace.dashboard,marketplace.assortiment,marketplace.team,marketplace.company,admin.products,admin.companies,admin.files,admin.banners',
            'placement' => 'required|in:icon,tooltip,modal,sidebar',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'title_uz' => 'required|string|max:255',
            'content_uz' => 'required|string',
            'title_ru' => 'required|string|max:255',
            'content_ru' => 'required|string',
            'title_en' => 'required|string|max:255',
            'content_en' => 'required|string',
        ]);

        $article = HelpArticle::create([
            'slug' => $this->generateUniqueSlug($validated['title_en'], 'help_articles'),
            'section' => $validated['section'],
            'placement' => $validated['placement'],
            'is_active' => $validated['is_active'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $article->translations()->create([
                'lang' => $lang,
                'title' => $validated["title_$lang"],
                'content' => $validated["content_$lang"],
            ]);
        }

        return redirect()->route('admin.help.index')->with('success', __('admin.help.created'));
    }

    public function edit(HelpArticle $help)
    {
        $article = $help->load('translations');
        return view('admin.help.form', compact('article'));
    }

    public function update(Request $request, HelpArticle $help)
    {
        $validated = $request->validate([
            'section' => 'required|in:marketplace.dashboard,marketplace.assortiment,marketplace.team,marketplace.company,admin.products,admin.companies,admin.files,admin.banners',
            'placement' => 'required|in:icon,tooltip,modal,sidebar',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'title_uz' => 'required|string|max:255',
            'content_uz' => 'required|string',
            'title_ru' => 'required|string|max:255',
            'content_ru' => 'required|string',
            'title_en' => 'required|string|max:255',
            'content_en' => 'required|string',
        ]);

        $help->update([
            'section' => $validated['section'],
            'placement' => $validated['placement'],
            'is_active' => $validated['is_active'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $help->translations()
                ->where('lang', $lang)
                ->update([
                    'title' => $validated["title_$lang"],
                    'content' => $validated["content_$lang"],
                ]);
        }

        return redirect()->route('admin.help.index')->with('success', __('admin.help.updated'));
    }

    public function destroy(HelpArticle $help)
    {
        $help->translations()->delete();
        $help->delete();

        return redirect()->route('admin.help.index')->with('success', __('admin.help.deleted'));
    }
}
