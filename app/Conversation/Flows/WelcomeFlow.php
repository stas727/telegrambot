<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:24
 */

namespace App\Conversation\Flow;

use App\Conversation\Traits\HasStates;
use App\Conversation\Traits\HasTriggers;
use App\Conversation\Traits\SendMessages;


class WelcomeFlow extends AbstractFlow
{
    use HasTriggers, HasStates, SendMessages;

    public function __construct()
    {
        //Triggers
        $this
            ->addTrigger('/start')
            ->addTrigger('привет');
        //States
        $this
            ->addStates('sayHello');

    }

    protected function sayHello()
    {
        $this->log('sayHello',  []);

        $this->reply('Привет в Крокус Студио :)');

        $this->runFlow(CategoryFlow::class);
    }
}