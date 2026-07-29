<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'name', 'description', 'file_path', 'file_name',
        'file_size', 'file_type', 'version',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_file')
            ->withPivot('type', 'language', 'sort_order')
            ->withTimestamps();
    }

    public function getUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '—';
        $kb = $this->file_size / 1024;
        if ($kb < 1024) return round($kb, 1) . ' KB';
        return round($kb / 1024, 1) . ' MB';
    }
}
