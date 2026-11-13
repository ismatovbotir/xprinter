<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $kb_article_id
 * @property string $lang
 * @property string $title
 * @property string|null $excerpt
 * @property string $body
 */
class KbArticleTranslation extends Model
{
    protected $table = 'kb_article_translations';

    protected $fillable = ['kb_article_id', 'lang', 'title', 'excerpt', 'body'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(KbArticle::class, 'kb_article_id');
    }

    /** Body with only the simple tags we allow in articles; script/style blocks are removed with their content. */
    public function safeBody(): string
    {
        $body = preg_replace('#<(script|style|iframe|object|embed)\b[^>]*>.*?</\1>#is', '', (string) $this->body) ?? '';
        $body = preg_replace('#\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $body) ?? '';          // onclick= etc.
        $body = preg_replace('#(href|src)\s*=\s*(["\']?)\s*javascript:[^"\'>\s]*#i', '$1=$2#', $body) ?? '';

        return strip_tags($body, '<p><br><ol><ul><li><strong><b><em><i><code><pre><a><h3><h4><table><thead><tbody><tr><th><td><img>');
    }

    /** Plain text for meta descriptions, search snippets and the chatbot. */
    public function plainText(): string
    {
        return trim(preg_replace('/\s+/u', ' ', strip_tags($this->safeBody())) ?? '');
    }
}
