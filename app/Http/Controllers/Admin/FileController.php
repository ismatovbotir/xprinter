<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FileController extends Controller
{
    public function index(): View
    {
        $files = File::latest()->paginate(20);
        return view('admin.files.index', compact('files'));
    }

    public function create(): View
    {
        return view('admin.files.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'version'     => ['nullable', 'string', 'max:50'],
            'file'        => ['required', 'file', 'max:51200'],
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['file_path'] = $file->store('downloads', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getClientOriginalExtension();

            File::create($data);
        }

        return redirect()->route('admin.files.index')->with('success', "«{$data['name']}» yuklandi");
    }

    public function edit(File $file): View
    {
        return view('admin.files.form', compact('file'));
    }

    public function update(Request $request, File $file): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'version'     => ['nullable', 'string', 'max:50'],
            'file'        => ['nullable', 'file', 'max:51200'],
        ]);

        if ($request->hasFile('file')) {
            if ($file->file_path) {
                Storage::disk('public')->delete($file->file_path);
            }
            $f = $request->file('file');
            $data['file_path'] = $f->store('downloads', 'public');
            $data['file_name'] = $f->getClientOriginalName();
            $data['file_size'] = $f->getSize();
            $data['file_type'] = $f->getClientOriginalExtension();
        }

        $file->update($data);

        return redirect()->route('admin.files.index')->with('success', "«{$file->name}» yangilandi");
    }

    public function destroy(File $file): RedirectResponse
    {
        $name = $file->name;
        if ($file->file_path) {
            Storage::disk('public')->delete($file->file_path);
        }
        $file->delete();

        return redirect()->route('admin.files.index')->with('success', "«{$name}» o'chirildi");
    }
}
