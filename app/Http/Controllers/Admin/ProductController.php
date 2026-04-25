<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()   { return view('admin.products.index'); }
    public function create()  { return view('admin.products.create'); }
    public function store()   { return redirect()->route('admin.products.index'); }
    public function show($model)  { return view('admin.products.show', compact('model')); }
    public function edit($model)  { return view('admin.products.edit', compact('model')); }
    public function update($model){ return redirect()->route('admin.products.index'); }
    public function destroy($model){ return redirect()->route('admin.products.index'); }
}
