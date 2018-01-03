<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:24
 */

namespace App\Conversation\Flow;
use Telegram;

class WelcomeFlow extends AbstractFlow
{

    protected $triggers = [
        '/start',
        '/привет'
    ];

    protected $states = [
        'first'
    ];

    protected function first()
    {
        Telegram::sendMessage([
            'chat_id' => $this->user->chat_id,
            'text' => 'Привет от Крокус Студио :)'
        ]);
    }
}