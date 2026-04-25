<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ParameterController extends Controller
{
    public function index()   { return view('admin.parameters.index'); }
    public function create()  { return view('admin.parameters.create'); }
    public function store()   { return redirect()->route('admin.parameters.index'); }
    public function show($model)  { return view('admin.parameters.show', compact('model')); }
    public function edit($model)  { return view('admin.parameters.edit', compact('model')); }
    public function update($model){ return redirect()->route('admin.parameters.index'); }
    public function destroy($model){ return redirect()->route('admin.parameters.index'); }
}
