<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);

        // Логируем входящие данные
        Log::info('Telegram webhook:', $update->toArray());

        // Пример обработки команды /start
        if ($update->getMessage()->getText() === '/start') {
            Telegram::sendMessage([
                'chat_id' => $update->getChat()->getId(),
                'text' => 'Привет! Я бот для напоминаний. айди чата'.$update->getChat()->getId()
            ]);
        }

        return response()->json(['ok' => true]);
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
