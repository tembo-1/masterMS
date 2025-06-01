<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);
        Log::info($update);
        dd($update->getMessage());
        dd($update->getMessage());
        $message = $update->getMessage();
        $chatId = $message->getChat()->getId();
        $text = $message->getText();

        if ($text === '/start') {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Добро пожаловать! Отправьте /add для создания напоминания."
            ]);
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
