<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around the Telegram Bot API. Every call is best-effort: a
 * missing/invalid token or a network failure is logged, never thrown — a
 * broken bot config must not turn into a 500 on the webhook endpoint.
 */
class TelegramBotService
{
    private ?string $token;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token');
    }

    public function isConfigured(): bool
    {
        return (bool) $this->token && $this->token !== 'YOUR_TELEGRAM_BOT_TOKEN';
    }

    public function sendMessage(int|string $chatId, string $text, array $extra = []): void
    {
        $this->call('sendMessage', array_merge([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'HTML',
        ], $extra));
    }

    /** Asks the user to share their own phone number via Telegram's native contact button. */
    public function requestContact(int|string $chatId, string $text): void
    {
        $this->sendMessage($chatId, $text, [
            'reply_markup' => json_encode([
                'keyboard' => [[
                    ['text' => "📱 Telefon raqamni ulashish", 'request_contact' => true],
                ]],
                'resize_keyboard'   => true,
                'one_time_keyboard' => true,
            ]),
        ]);
    }

    public function removeKeyboard(int|string $chatId, string $text): void
    {
        $this->sendMessage($chatId, $text, [
            'reply_markup' => json_encode(['remove_keyboard' => true]),
        ]);
    }

    public function sendWebAppButton(int|string $chatId, string $text, string $buttonText, string $url): void
    {
        $this->sendMessage($chatId, $text, [
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => $buttonText, 'web_app' => ['url' => $url]],
                ]],
            ]),
        ]);
    }

    public function setWebhook(string $url, ?string $secret = null): array
    {
        $params = ['url' => $url];
        if ($secret) {
            $params['secret_token'] = $secret;
        }

        return $this->call('setWebhook', $params) ?? ['ok' => false, 'description' => 'request failed'];
    }

    /**
     * Verifies data received from a Telegram WebApp (the `Telegram.WebApp.initData`
     * string) using the HMAC check documented at
     * https://core.telegram.org/bots/webapps#validating-data-received-via-the-mini-app
     *
     * @return array<string,mixed>|null Parsed fields (including decoded 'user') if valid, null otherwise.
     */
    public function validateInitData(string $initData): ?array
    {
        if (!$this->isConfigured() || $initData === '') {
            return null;
        }

        parse_str($initData, $data);

        if (!isset($data['hash'])) {
            return null;
        }

        $hash = $data['hash'];
        unset($data['hash']);
        ksort($data);

        $checkString = collect($data)
            ->map(fn($value, $key) => "{$key}={$value}")
            ->implode("\n");

        $secretKey = hash_hmac('sha256', $this->token, 'WebAppData', true);
        $calculatedHash = hash_hmac('sha256', $checkString, $secretKey);

        if (!hash_equals($calculatedHash, $hash)) {
            return null;
        }

        if (isset($data['user'])) {
            $data['user'] = json_decode($data['user'], true);
        }

        return $data;
    }

    private function call(string $method, array $params = []): ?array
    {
        if (!$this->isConfigured()) {
            Log::warning("Telegram bot: {$method} skipped — bot token not configured");
            return null;
        }

        try {
            $response = Http::asForm()->post("https://api.telegram.org/bot{$this->token}/{$method}", $params);

            if (!$response->successful()) {
                Log::warning("Telegram bot: {$method} failed", ['response' => $response->body()]);
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::warning("Telegram bot: {$method} threw", ['message' => $e->getMessage()]);

            return null;
        }
    }
}
