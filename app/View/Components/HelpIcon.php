<?php

namespace App\View\Components;

use App\Models\HelpArticle;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HelpIcon extends Component
{
    public ?HelpArticle $article = null;
    public string $section;
    public string $placement;

    public function __construct(string $section, ?HelpArticle $article = null)
    {
        $this->section = $section;

        // Load article if not passed, find by section
        $this->article = $article ?? HelpArticle::where('section', $section)
            ->where('is_active', true)
            ->first();

        $this->placement = $this->article?->placement ?? 'icon';
    }

    public function render(): View|Closure|string
    {
        if (!$this->article) {
            return '';
        }

        return view('components.help-icon', [
            'article' => $this->article,
            'placement' => $this->placement,
            'translation' => $this->article->translation,
        ]);
    }
}
