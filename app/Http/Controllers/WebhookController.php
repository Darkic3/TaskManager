<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $chatId = $request->input('sender_id');
        $messageText = $request->input('message');

        if (!$chatId || !$messageText) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            return $this->handleRegistration($chatId, $messageText);
        }

        return $this->handleTask($user, $messageText);
    }

    protected function handleRegistration($chatId, $messageText)
    {
        Log::info("Guest user ($chatId) sent: $messageText");

        return response()->json([
            'action' => 'send_message',
            'text' => "سلام! شما هنوز در سیستم ثبت‌نام نکرده‌اید. لطفاً ابتدا از طریق پنل وب یا ارسال شماره تلفن اقدام کنید."
        ]);
    }

    protected function handleTask(User $user, $messageText)
    {
        $task = Task::createFromTelegram($user->id, $messageText);

        Log::info("Task created for user {$user->id}: {$task->title}");

        return response()->json([
            'action' => 'send_message',
            'text' => "✅ تسک با موفقیت ثبت شد:\n\n📌 {$task->title}\n📂 دسته: " . ($task->category ?? 'بدون دسته')
        ]);
    }
}
