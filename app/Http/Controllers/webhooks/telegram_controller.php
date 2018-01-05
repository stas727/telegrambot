<?php

namespace App\Http\Controllers\webhooks;

use App\Conversation\Conversation;
use App\Repositories\MessageRepository;
use Log;
use Telegram;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class telegram_controller extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function process(UserRepository $users, MessageRepository $messages, Conversation $conversation)
    {
        $update = Telegram::getWebhookUpdates();

        Log::debug(
            'Telegram.process', [
                'update' => $update->update->toArray()
            ]
        );

        $message = $update->getMessage();

        $user = $message->getFrom();

        //сохраняем пользователя
        $user = $users->store(
            $user->getId(),
            $user->getFirstName() ?? '',
            $user->getLastName() ?? '',
            $user->getUsername() ?? ''
        );


        //сохраняем сообщение
        $message = $messages->store($user, $message->getMessageId(), $message->getText() ?? '');

        $conversation->start($user, $message);
        //ответим :)
        /*if (hash_equals($message->getText(), '/start')) {
            Telegram::sendMessage([
                'chat_id' => $user->chat_id,
                'text' => 'Привет в чате от Крокуса:)'
            ]);
        }*/
    }

}
