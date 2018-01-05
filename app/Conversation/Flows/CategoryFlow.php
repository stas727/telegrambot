<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:23
 */

namespace App\Conversation\Flow;

use Telegram\Bot\Laravel\Facades\Telegram;

class CategoryFlow extends AbstractFlow
{

    protected $triggers = [];


    public function first()
    {
        Telegram::sendMessage([
           'chat_id' => $this->user->chat_id,
            'text' => 'Список категорий'
        ]);
    }
}