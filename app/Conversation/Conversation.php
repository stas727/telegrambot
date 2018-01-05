<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:19
 */

namespace App\Conversation;

use App\Conversation\Flow\AbstractFlow;
use App\Conversation\Flow\WelcomeFlow;
use App\Entities\Message;
use App\Entities\User;
use Log;

class Conversation
{

    protected $flows = [
        WelcomeFlow::class,
    ];

    public function start(User $user, Message $message)
    {

        Log::debug(
            'Conversation.start', [
                'user' => $user->toArray(),
                'message' => $message->toArray()
            ]
        );

        foreach ($this->flows as $flow) {
            /**
             * @var AbstractFlow $flow
             */
            $flow = app($flow);

            $flow->setUser($user);
            $flow->setMessage($message);
            $state = $flow->run();
            if (!is_null($state)) {
                $this->context->save($user, $flow, $state);
                break;
            }
        }
    }
}