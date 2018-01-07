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
use App\Conversation\Traits\InteractsWithContext;
use App\Traits\Loggable;
use Log;

class Conversation
{
    use Loggable, InteractsWithContext;
    protected $flows;

    function __construct(array $flows = [])
    {
        $this->flows = $flows;
    }

    public function start($user, $message)
    {
        $this->log('start', [
                'user' => $user->toArray(),
                'message' => $message->toArray()
            ]
        );
        $this->user = $user;

        $context = $this->context();

        if($context->hasFlow()){
            $flow = $context->getFlow();
            $flow->setUser($this->user);
            $flow->setMessage($message);
            $flow->handle();
            return;
        }



        foreach ($this->flows as $flow) {
            /**
             * @var AbstractFlow $flow
             */
            LOG::debug('SHOW CURRENT CONTEXT', ['context' => $context]);
            $flow = app($flow);

            $flow->setUser($user);
            $flow->setMessage($message);

            if($flow->handle()){
                break;
            };
        }
    }
}