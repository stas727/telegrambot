<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:26
 */

namespace App\Conversation\Flow;

use App\Entities\Message;
use App\Entities\User;
use Log;

abstract class AbstractFlow
{

    /**
     * @var User $user
     */
    protected $user;
    /**
     * @var Message $message
     */
    protected $message;

    protected $triggers = [];

    protected $states = [];

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Message $message
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @param string|null $state
     * @return string|null $state
     */
    public function run($state = null)
    {
        Log::debug(
            static::class . '.run', [
                'user' => $this->user->toArray(),
                'message' => $this->message->toArray(),
                'state' => $state
            ]
        );

        foreach ($this->triggers as $trigger) {
            if (hash_equals($trigger, $this->message->text)) {
                $state = 'first';
            }
        }

        if (is_null($state)) {
            return null;
        }

        $this->$state();

        return $state;

    }

    protected abstract function first();

}