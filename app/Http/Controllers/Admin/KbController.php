<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\GeneratesSlug;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\File;
use App\Models\KbArticle;
use App\Services\Chatbot\KnowledgeBase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin CRUD for the knowledge base (/support).
 */
class KbController extends Controller
{
    use GeneratesSlug;

    public function index(Request $request): View
    {
        $query = KbArticle::with(['translations', 'products'])
            ->withCount('products')
            ->orderBy('type')
            ->orderBy('sort_order');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $articles = $query->paginate(30)->withQueryString();

        return view('admin.kb.index', ['articles' => $articles, 'types' => KbArticle::TYPES]);
    }

    public function create(): View
    {
        return view('admin.kb.form', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $article = KbArticle::create([
            'slug'         => $this->generateUniqueSlug($data['title_en'], 'kb_articles'),
            'type'         => $data['type'],
            'category_id'  => $data['category_id'] ?? null,
            'difficulty'   => $data['difficulty'],
            'keywords'     => $data['keywords'] ?? null,
            'is_published' => (bool) ($data['is_published'] ?? false),
            'sort_order'   => $data['sort_order'] ?? 0,
        ]);

        $this->saveTranslations($article, $data);
        $article->products()->sync($data['products'] ?? []);
        $article->files()->sync($data['files'] ?? []);

        KnowledgeBase::forgetCache();

        return redirect()->route('admin.kb.index')->with('success', 'Maqola yaratildi.');
    }

    public function edit(KbArticle $kb): View
    {
        $kb->load(['translations', 'products', 'files']);

        return view('admin.kb.form', $this->formData() + ['article' => $kb]);
    }

    public function update(Request $request, KbArticle $kb): RedirectResponse
    {
        $data = $this->validated($request);

        $kb->update([
            'type'         => $data['type'],
            'category_id'  => $data['category_id'] ?? null,
            'difficulty'   => $data['difficulty'],
            'keywords'     => $data['keywords'] ?? null,
            'is_published' => (bool) ($data['is_published'] ?? false),
            'sort_order'   => $data['sort_order'] ?? 0,
        ]);

        $this->saveTranslations($kb, $data);
        $kb->products()->sync($data['products'] ?? []);
        $kb->files()->sync($data['files'] ?? []);

        KnowledgeBase::forgetCache();

        return redirect()->route('admin.kb.index')->with('success', 'Maqola yangilandi.');
    }

    public function destroy(KbArticle $kb): RedirectResponse
    {
        $kb->delete();
        KnowledgeBase::forgetCache();

        return redirect()->route('admin.kb.index')->with('success', 'Maqola o\'chirildi.');
    }

    // ── Internals ───────────────────────────────────────────

    private function validated(Request $request): array
    {
        return $request->validate([
            'type'         => ['required', 'in:' . implode(',', array_keys(KbArticle::TYPES))],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'difficulty'   => ['required', 'in:' . implode(',', KbArticle::DIFFICULTIES)],
            'keywords'     => ['nullable', 'string', 'max:2000'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order'   => ['nullable', 'integer', 'min:0', 'max:65535'],
            'products'     => ['nullable', 'array'],
            'products.*'   => ['integer', 'exists:products,id'],
            'files'        => ['nullable', 'array'],
            'files.*'      => ['integer', 'exists:files,id'],
            'title_uz'     => ['required', 'string', 'max:255'],
            'title_ru'     => ['required', 'string', 'max:255'],
            'title_en'     => ['required', 'string', 'max:255'],
            'excerpt_uz'   => ['nullable', 'string', 'max:600'],
            'excerpt_ru'   => ['nullable', 'string', 'max:600'],
            'excerpt_en'   => ['nullable', 'string', 'max:600'],
            'body_uz'      => ['required', 'string'],
            'body_ru'      => ['required', 'string'],
            'body_en'      => ['required', 'string'],
        ]);
    }

    private function saveTranslations(KbArticle $article, array $data): void
    {
        foreach (['uz', 'ru', 'en'] as $lang) {
            $article->translations()->updateOrCreate(['lang' => $lang], [
                'title'   => $data["title_{$lang}"],
                'excerpt' => $data["excerpt_{$lang}"] ?? null,
                'body'    => $data["body_{$lang}"],
            ]);
        }
    }

    private function formData(): array
    {
        return [
            'types'        => KbArticle::TYPES,
            'difficulties' => KbArticle::DIFFICULTIES,
            'categories'   => Category::with(['translations', 'products' => fn ($q) => $q->orderBy('model_number')])->get(),
            'fileOptions'  => File::orderBy('name')->get(['id', 'name', 'version', 'file_name']),
        ];
    }
}
