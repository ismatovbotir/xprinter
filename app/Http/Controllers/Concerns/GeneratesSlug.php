<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait GeneratesSlug
{
    protected function generateUniqueSlug(string $source, string $table, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $slug = $base;
        $i = 2;

        while (
            DB::table($table)
                ->where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
