<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()   { return view('admin.categorys.index'); }
    public function create()  { return view('admin.categorys.create'); }
    public function store()   { return redirect()->route('admin.categorys.index'); }
    public function show($model)  { return view('admin.categorys.show', compact('model')); }
    public function edit($model)  { return view('admin.categorys.edit', compact('model')); }
    public function update($model){ return redirect()->route('admin.categorys.index'); }
    public function destroy($model){ return redirect()->route('admin.categorys.index'); }
}
