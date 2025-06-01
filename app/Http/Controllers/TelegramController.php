<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);
        $message = $update->getMessage();
        $chatId = $message->getChat()->getId();
        $text = $message->getText();

        // Обработка /start
        if ($text === '/start') {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Привет! Я помогу напоминать о заказах.\n\n" .
                    "Добавить клиента:\n" .
                    "/add Иван 79161112233 Чистка_котла 2024-09-15"
            ]);
            return;
        }

        // Обработка /add
        if (str_starts_with($text, '/add')) {
            $this->handleAddCommand($chatId, $text);
        }
    }

    private function handleAddCommand($chatId, $text)
    {
        // Парсинг: /add Иван 79161112233 Чистка_котла 2024-09-15
        if (!preg_match('/\/add (\w+) (\d{11}) (.+) (\d{4}-\d{2}-\d{2})/', $text, $matches)) {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "❌ Ошибка формата. Пример:\n" .
                    "/add Иван 79161112233 Чистка_котла 2024-09-15"
            ]);
            return;
        }


        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "✅ Клиент {$matches[1]} добавлен!\n" .
                "Напоминание: {$matches[4]}\n" .
                "Услуга: {$matches[3]}"
        ]);
    }
}
