<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::post('/bot/webhook', [WebhookController::class, 'handle']);
