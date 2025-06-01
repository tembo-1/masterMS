<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/telegram-webhook', [TelegramController::class, 'handleWebhook']);
