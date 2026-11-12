<?php

namespace App\Livewire;

use App\Services\Chatbot\ChatbotService;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;

/** Floating site chat. History lives in the visitor's session only. */
class ChatWidget extends Component
{
    private const SESSION_KEY = 'chatbot.web';
    private const MAX_HISTORY = 20;

    public array $messages = [];

    #[Validate('required|string|max:1000')]
    public string $input = '';

    public bool $pending = false;

    public function mount(): void
    {
        $this->messages = session(self::SESSION_KEY, []);
    }

    /** Step 1: show the visitor's message right away, then ask the client to request the answer. */
    public function send(): void
    {
        $this->validate();

        $key = 'chatbot:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $this->push('assistant', app()->getLocale() === 'ru'
                ? 'Слишком много сообщений. Подождите минуту.'
                : "Juda ko'p xabar. Bir daqiqa kuting.");

            return;
        }

        RateLimiter::hit($key, 60);

        $this->push('user', trim($this->input));
        $this->input   = '';
        $this->pending = true;

        $this->dispatch('chatbot-pending');
    }

    /** Step 2: run the (slow) model and append its reply. */
    public function answer(ChatbotService $chatbot): void
    {
        $last = end($this->messages);

        if (!$this->pending || !$last || $last['role'] !== 'user') {
            $this->pending = false;

            return;
        }

        $history = array_slice($this->messages, 0, -1);
        $reply   = $chatbot->respond($last['content'], 'web', auth()->user(), $history);

        $this->pending = false;
        $this->push('assistant', $reply->text);
    }

    public function clear(): void
    {
        $this->messages = [];
        session()->forget(self::SESSION_KEY);
    }

    /** Escaped text with clickable links and line breaks. */
    public function format(string $text): string
    {
        $html = e(preg_replace('/\*\*(.+?)\*\*/s', '$1', $text));
        $html = preg_replace('~(https?://[^\s<]+[^\s<.,;:!?)])~', '<a href="$1" target="_blank" rel="noopener">$1</a>', $html);

        return nl2br($html);
    }

    private function push(string $role, string $content): void
    {
        $this->messages[] = ['role' => $role, 'content' => $content];
        $this->messages   = array_slice($this->messages, -self::MAX_HISTORY);

        session([self::SESSION_KEY => $this->messages]);
    }

    public function render()
    {
        return view('livewire.chat-widget');
    }
}
