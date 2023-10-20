<?php

namespace App\Logging\Telegram;

use App\Services\Telegram\TelegramBotApi;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class TelegramLoggerHandler extends AbstractProcessingHandler
{
    protected int $chatId;
    protected string $token;

    protected string $dateFormat = 'Y-m-d H:i:s';
    protected string $output = '%datetime% -> %level_name% -> %message% %context% %extra%';

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);

        parent::__construct($level);

        $this->chatId = (int) $config['chat_id'];
        $this->token = $config['token'];

        $this->setFormatter(new LineFormatter(
            $this->output,
            $this->dateFormat,
            ignoreEmptyContextAndExtra: true,
        ));
    }

    protected function write(LogRecord $record): void
    {
        TelegramBotApi::sendMessage(
            $this->token,
            $this->chatId,
            $record->formatted
        );
    }
}
