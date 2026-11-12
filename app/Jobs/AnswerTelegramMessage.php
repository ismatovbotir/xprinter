<?php

namespace App\Jobs;

use App\Models\ClientMessage;
use App\Models\User;
use App\Services\Chatbot\ChatbotService;
use App\Services\TelegramBotService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/** Answers one Telegram client message with the site bot (run synchronously from the webhook). */
class AnswerTelegramMessage implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;
    public int $timeout = 120;

    public function __construct(public User $user, public ClientMessage $message) {}

    public function handle(ChatbotService $chatbot, TelegramBotService $telegram): void
    {
        $chatId = $this->user->telegram_chat_id;

        $history = ClientMessage::where('user_id', $this->user->id)
            ->where('id', '<', $this->message->id)
            ->where('created_at', '>=', now()->subHours(2))
            ->latest('id')
            ->limit(6)
            ->get()
            ->reverse()
            ->map(fn ($m) => ['role' => $m->sender === 'client' ? 'user' : 'assistant', 'content' => $m->body])
            ->values()
            ->all();

        $reply = $chatbot->respond($this->message->body, 'telegram', $this->user, $history);

        ClientMessage::create([
            'user_id' => $this->user->id,
            'sender'  => 'bot',
            'body'    => $reply->text,
            'is_read' => $reply->answered, // unanswered ones stay unread for the admin inbox
        ]);

        $telegram->sendMessage($chatId, $reply->text);
    }
}
