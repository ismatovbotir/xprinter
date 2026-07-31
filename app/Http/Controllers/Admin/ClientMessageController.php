<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientMessage;
use App\Models\User;
use App\Services\TelegramBotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientMessageController extends Controller
{
    public function index(): View
    {
        $clients = User::where('role', 'client')
            ->whereHas('clientMessages')
            ->withCount(['clientMessages as unread_count' => fn ($q) => $q->where('sender', 'client')->where('is_read', false)])
            ->with(['clientMessages' => fn ($q) => $q->latest()->limit(1)])
            ->get()
            ->sortByDesc(fn ($user) => $user->clientMessages->first()?->created_at)
            ->values();

        return view('admin.messages.index', compact('clients'));
    }

    public function show(User $user): View
    {
        $messages = $user->clientMessages()->with('admin')->oldest()->get();

        $user->clientMessages()->where('sender', 'client')->where('is_read', false)->update(['is_read' => true]);

        return view('admin.messages.show', compact('user', 'messages'));
    }

    public function reply(Request $request, User $user, TelegramBotService $bot): RedirectResponse
    {
        $request->validate([
            'body' => 'required|string|max:4000',
        ]);

        ClientMessage::create([
            'user_id'  => $user->id,
            'admin_id' => $request->user()->id,
            'sender'   => 'admin',
            'body'     => $request->input('body'),
        ]);

        if ($user->telegram_chat_id) {
            $bot->sendMessage($user->telegram_chat_id, $request->input('body'));
        }

        return redirect()->route('admin.messages.show', $user)->with('success', 'Javob yuborildi');
    }
}
