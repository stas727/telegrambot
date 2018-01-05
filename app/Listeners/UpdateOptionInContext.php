<?php
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 05.01.18
 * Time: 20:32
 */

namespace App\Listeners;


use App\Conversation\Context;
use App\Events\OptionChanged;
use Log;

class UpdateOptionInContext
{
    public function handle(OptionChanged $event)
    {
        $key = $event->getKey();
        $value = $event->getValue();
        $user = $event->getUser();

        Log::debug(
            'UpdateOptionInContext', [
                'key' => $key,
                'value' => $value,
                'user' => $user,
            ]
        );

        Context::update($user, [$key => $value]);
    }
}