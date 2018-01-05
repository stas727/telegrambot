<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:19
 */

namespace App\Conversation;

use App\Conversation\Flow\AbstractFlow;
use App\Conversation\Flow\CategoryFlow;
use App\Conversation\Flow\WelcomeFlow;
use Log;

class Conversation
{

    protected $flows = [
        WelcomeFlow::class,
        CategoryFlow::class
    ];

    public function start($user, $message)
    {

        Log::debug(
            'Conversation.start', [
                'user' => $user->toArray(),
                'message' => $message->toArray()
            ]
        );


        $context = Context::get($user);

        foreach ($this->flows as $flow) {
            /**
             * @var AbstractFlow $flow
             */
            $flow = app($flow);

            $flow->setUser($user);
            $flow->setMessage($message);
            $flow->setContext($context);
            $flow->run();

        }
    }
}