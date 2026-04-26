<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();

        $view = match(true) {
            $user->isAdmin()              => 'admin.profile',
            $user->isOwner() || $user->isUser() => 'marketplace.profile',
            $user->isProducer()           => 'producer.profile',
            default                       => 'profile',
        };

        return view($view, compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:30'],
            'lang'      => ['required', 'in:uz,ru,en'],
        ]);

        $user->update($data);

        session(['lang' => $data['lang']]);

        return back()->with('success', 'Profil yangilandi');
    }
}
