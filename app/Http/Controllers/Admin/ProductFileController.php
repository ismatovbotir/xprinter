<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductFileController extends Controller
{
    public function attach(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'file_id'  => ['required', 'exists:files,id'],
            'type'     => ['required', 'in:driver,manual,spec,firmware,utility,other'],
            'language' => ['required', 'in:uz,ru,en'],
        ]);

        $file = File::findOrFail($data['file_id']);

        $product->files()->attach($file->id, [
            'type'     => $data['type'],
            'language' => $data['language'],
        ]);

        return back()->with('success', "«{$file->name}» biriktirshdi");
    }

    public function detach(Product $product, File $file): RedirectResponse
    {
        $product->files()->detach($file->id);
        return back()->with('success', "«{$file->name}» o'chirildi");
    }
}
