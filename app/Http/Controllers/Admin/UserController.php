<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()   { return view('admin.users.index'); }
    public function create()  { return view('admin.users.create'); }
    public function store()   { return redirect()->route('admin.users.index'); }
    public function show($model)  { return view('admin.users.show', compact('model')); }
    public function edit($model)  { return view('admin.users.edit', compact('model')); }
    public function update($model){ return redirect()->route('admin.users.index'); }
    public function destroy($model){ return redirect()->route('admin.users.index'); }
}
