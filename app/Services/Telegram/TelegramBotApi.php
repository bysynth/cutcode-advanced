<?php

namespace App\Services\Telegram;

use App\Exceptions\Telegram\TelegramBotApiException;
use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, int $chat_id, string $text): bool
    {
        try {
            $status = Http::get(self::HOST . $token . '/sendMessage', [
                'chat_id' => $chat_id,
                'text' => $text,
            ])->json('ok');

            return $status ?: throw new TelegramBotApiException('Не могу отправить сообщение в TG');

        } catch (TelegramBotApiException $e) {
            report($e);

            return false;
        }
    }
}
