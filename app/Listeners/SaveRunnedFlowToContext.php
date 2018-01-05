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

class SaveRunnedFlowToContext
{
    public function handle(FlowRunned $event)
    {
        Context::save($event->getUser(), $event->getFlow(), $event->getState());
    }
}