<?php

namespace App\Services\Chatbot;

final class ChatbotReply
{
    public function __construct(
        public readonly string $text,
        public readonly bool $answered,
        public readonly string $lang,
    ) {}
}
