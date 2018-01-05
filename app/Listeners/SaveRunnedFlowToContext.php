<?php
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 05.01.18
 * Time: 12:40
 */

namespace App\Listeners;


use App\Conversation\Context;
use App\Events\FlowRunned;
use Log;

class SaveRunnedFlowToContext
{
    public function handle(FlowRunned $event)
    {

        $user = $event->getUser();
        $flow = $event->getFlow();
        $state = $event->getState();
        $option = $event->getOptions();

        Log::debug(
            static::class . '.run', [
                'user' => $user->toArray(),
                'flow' => get_class($flow),
                'state' => $state,
                'option' => $option
            ]
        );
        Context::save($user, $flow, $state, $option);
    }
}