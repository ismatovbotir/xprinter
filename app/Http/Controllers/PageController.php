<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $content = HomeContent::current();

        return view('pages.about', compact('content'));
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}
