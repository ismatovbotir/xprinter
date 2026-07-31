<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:webhook:set';

    protected $description = 'Register this app\'s webhook URL with the Telegram bot API';

    public function handle(TelegramBotService $bot): int
    {
        if (!$bot->isConfigured()) {
            $this->error('TELEGRAM_BOT_TOKEN is not set in .env — add the real token from @BotFather first.');

            return self::FAILURE;
        }

        $url = route('telegram.webhook');

        if (!str_starts_with($url, 'https://')) {
            $this->error("Telegram requires an HTTPS URL for webhooks — APP_URL currently resolves to: {$url}");

            return self::FAILURE;
        }

        $secret = config('services.telegram.webhook_secret');
        $result = $bot->setWebhook($url, $secret);

        if ($result['ok'] ?? false) {
            $this->info("Webhook registered: {$url}");

            return self::SUCCESS;
        }

        $this->error('Telegram rejected the webhook: ' . ($result['description'] ?? 'unknown error'));

        return self::FAILURE;
    }
}
