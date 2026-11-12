<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotQuestion;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\TelegramBotService;

/**
 * Answers client questions (web widget + Telegram) from site knowledge via the
 * rule-based SiteBot (no external AI). When the bot does not know, the question is
 * saved for the admin and the admin is alerted in Telegram.
 */
class ChatbotService
{
    /** Phrases that mean "let me talk to a human". */
    private const HANDOFF_WORDS = ['operator', 'оператор', 'администратор', 'менеджер', 'odam bilan', 'jonli', 'живой'];

    public function __construct(
        private SiteBot $bot,
        private TelegramBotService $telegram,
    ) {}

    /**
     * @param  array<int, array{role:string, content:string}>  $history  previous turns, oldest first
     */
    public function respond(string $question, string $channel, ?User $user = null, array $history = []): ChatbotReply
    {
        $question = trim($question);
        $lang     = self::detectLang($question);

        $answer = $this->wantsHuman($question) ? null : $this->bot->answer($question, $lang, $history);

        if ($answer === null) {
            $this->escalate($question, $channel, $lang, $user);

            return new ChatbotReply($this->unknownMessage($lang, $channel), false, $lang);
        }

        return new ChatbotReply($answer, true, $lang);
    }

    /** Cyrillic text → ru, otherwise uz (latin). */
    public static function detectLang(string $text): string
    {
        return preg_match('/\p{Cyrillic}/u', $text) ? 'ru' : 'uz';
    }

    private function wantsHuman(string $question): bool
    {
        $q = mb_strtolower($question);

        foreach (self::HANDOFF_WORDS as $word) {
            if (str_contains($q, $word)) {
                return true;
            }
        }

        return false;
    }

    private function escalate(string $question, string $channel, string $lang, ?User $user): void
    {
        $record = ChatbotQuestion::create([
            'user_id'  => $user?->id,
            'channel'  => $channel,
            'lang'     => $lang,
            'question' => mb_substr($question, 0, 2000),
        ]);

        $adminChat = config('services.telegram.chat_id');

        if (!$adminChat) {
            return;
        }

        $who = $user ? trim($user->name . ' ' . $user->last_name) . ($user->phone ? " ({$user->phone})" : '') : 'Mehmon (sayt)';

        $this->telegram->sendMessage(
            $adminChat,
            "🤖 Bot javob bera olmadi\n\n"
            . "Kimdan: {$who}\nKanal: {$channel}\n\n"
            . "Savol: " . mb_substr($question, 0, 1000) . "\n\n"
            . 'Javob berish va botni o\'rgatish: ' . route('admin.chatbot.questions.show', $record)
        );
    }

    private function unknownMessage(string $lang, string $channel): string
    {
        $s       = SiteSetting::current();
        $contact = collect([$s->phone, $s->telegram])->filter()->implode(' · ');

        if ($lang === 'ru') {
            $text = 'К сожалению, на этот вопрос я пока не могу ответить точно. Я передал его администратору — '
                . ($channel === 'telegram' ? 'ответ придёт сюда в ближайшее время.' : 'он изучит вопрос.');

            return $contact && $channel === 'web' ? "{$text}\nДля быстрого ответа: {$contact}" : $text;
        }

        $text = "Kechirasiz, bu savolga hozircha aniq javob bera olmayman. Savolingizni administratorga yubordim — "
            . ($channel === 'telegram' ? 'tez orada shu yerga javob keladi.' : 'u savolni ko\'rib chiqadi.');

        return $contact && $channel === 'web' ? "{$text}\nTezroq javob uchun: {$contact}" : $text;
    }
}
