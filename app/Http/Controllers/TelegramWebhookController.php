<?php

namespace App\Http\Controllers;

use App\Models\ClientMessage;
use App\Models\User;
use App\Services\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TelegramWebhookController extends Controller
{
    private const REGISTRATION_TTL_MINUTES = 60 * 24;

    public function __construct(private TelegramBotService $bot) {}

    public function handle(Request $request): Response
    {
        if (!$this->hasValidSecret($request)) {
            abort(403);
        }

        $message = $request->input('message');

        if (!$message) {
            return response()->noContent();
        }

        $chatId = $message['chat']['id'] ?? null;
        $fromId = $message['from']['id'] ?? null;

        if (!$chatId || !$fromId) {
            return response()->noContent();
        }

        if ($contact = $message['contact'] ?? null) {
            $this->handleContact($chatId, $fromId, $contact);

            return response()->noContent();
        }

        if (($message['text'] ?? null) === '/start') {
            $this->handleStart($chatId);

            return response()->noContent();
        }

        if ($text = $message['text'] ?? null) {
            $this->handleText($chatId, $text);
        }

        return response()->noContent();
    }

    private function handleStart(int $chatId): void
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if ($user) {
            $this->sendMenu($chatId);

            return;
        }

        Cache::put($this->registrationKey($chatId), ['step' => 'name'], now()->addMinutes(self::REGISTRATION_TTL_MINUTES));

        $this->bot->sendMessage($chatId, "Xush kelibsiz! Ro'yxatdan o'tish uchun ismingizni kiriting:");
    }

    private function handleText(int $chatId, string $text): void
    {
        $state = Cache::get($this->registrationKey($chatId));

        if ($state) {
            $this->handleRegistrationStep($chatId, $state, trim($text));

            return;
        }

        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->bot->sendMessage($chatId, "Ro'yxatdan o'tish uchun /start buyrug'ini yuboring.");

            return;
        }

        ClientMessage::create([
            'user_id' => $user->id,
            'sender'  => 'client',
            'body'    => $text,
        ]);

        $this->bot->sendMessage($chatId, "Xabaringiz qabul qilindi. Administratsiya tez orada javob beradi.");
    }

    private function handleRegistrationStep(int $chatId, array $state, string $text): void
    {
        if ($text === '') {
            $this->bot->sendMessage($chatId, "Iltimos, matn kiriting.");

            return;
        }

        if ($state['step'] === 'name') {
            $state['name'] = $text;
            $state['step'] = 'surname';
            Cache::put($this->registrationKey($chatId), $state, now()->addMinutes(self::REGISTRATION_TTL_MINUTES));

            $this->bot->sendMessage($chatId, "Rahmat! Endi familiyangizni kiriting:");

            return;
        }

        if ($state['step'] === 'surname') {
            $state['surname'] = $text;
            $state['step'] = 'phone';
            Cache::put($this->registrationKey($chatId), $state, now()->addMinutes(self::REGISTRATION_TTL_MINUTES));

            $this->bot->requestContact($chatId, "Rahmat! Endi telefon raqamingizni ulashing:");

            return;
        }

        // step === 'phone' — waiting on the contact button, not free text
        $this->bot->sendMessage($chatId, "Iltimos, pastdagi tugma orqali telefon raqamingizni ulashing.");
    }

    private function handleContact(int $chatId, int $fromId, array $contact): void
    {
        // Only accept a contact the user shared about themselves — Telegram lets
        // a user forward someone else's saved contact, which must not link that
        // stranger's phone number to this chat.
        if ((int) ($contact['user_id'] ?? 0) !== $fromId) {
            $this->bot->sendMessage($chatId, "Iltimos, faqat o'zingizning telefon raqamingizni ulashing.");

            return;
        }

        $state = Cache::get($this->registrationKey($chatId), []);
        $phone = $this->normalizePhone($contact['phone_number']);
        $user  = User::firstOrNew(['phone' => $phone]);

        if (!$user->exists) {
            $name = $state['name'] ?? ($contact['first_name'] ?? 'Telegram foydalanuvchi');

            $user->fill([
                'name'      => $name,
                'last_name' => $state['surname'] ?? ($contact['last_name'] ?? null),
                'role'      => 'client',
                'lang'      => 'uz',
                'phone'     => $phone,
                'email'     => "tg{$fromId}@telegram.xprinter.uz",
                'password'  => Str::random(40),
            ]);
        }

        $user->telegram_chat_id = (string) $chatId;
        $user->save();

        Cache::forget($this->registrationKey($chatId));

        $this->bot->removeKeyboard($chatId, "Rahmat, {$user->name}! Ro'yxatdan o'tish yakunlandi.");
        $this->sendMenu($chatId);
    }

    private function sendMenu(int $chatId): void
    {
        $this->bot->sendWebAppButton(
            $chatId,
            "Katalogni ko'rish uchun tugmani bosing. Savol yoki so'rovingiz bo'lsa shu yerga yozing — administratsiya javob beradi.",
            "🛍 Katalogni ochish",
            route('telegram.app')
        );
    }

    private function registrationKey(int $chatId): string
    {
        return "tg_registration:{$chatId}";
    }

    private function normalizePhone(string $phone): string
    {
        return '+' . ltrim(preg_replace('/\D/', '', $phone), '+');
    }

    private function hasValidSecret(Request $request): bool
    {
        $expected = config('services.telegram.webhook_secret');

        if (!$expected) {
            return true;
        }

        return hash_equals($expected, (string) $request->header('X-Telegram-Bot-Api-Secret-Token'));
    }
}
