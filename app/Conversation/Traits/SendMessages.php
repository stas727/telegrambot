<?php
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 06.01.18
 * Time: 14:28
 */

namespace App\Conversation\Traits;


use Telegram\Bot\Keyboard\Keyboard;
use Telegram;

trait SendMessages
{


    protected $user;

    protected function reply(string $messages, array $buttons = [])
    {
        $params = [
            'chat_id' => $this->user->chat_id,
            'text' => $messages
        ];
        if (count($buttons) > 0) {
            $buttons = collect($buttons)->map(function ($value) {
                return [$value];
            });
            $params['reply_markup'] = Keyboard::make([
                'keyboard' => $buttons->toArray(),
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);
        }
        Telegram::sendMessage($params);
    }
    public function sendPhoto(){
         Telegram::sendPhoto([
            'chat_id' => $this->user->chat_id,
            'photo' => 'http://www.lg.com/us/images/cell-phones/MD05810711/md05803029-350x350.jpg',
            'caption' => 'Some caption'
        ]);
    }

}