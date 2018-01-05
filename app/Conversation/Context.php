<?php
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 04.01.18
 * Time: 23:52
 */

namespace App\Conversation;


use App\Conversation\Flow\AbstractFlow;
use App\Entities\User;

class Context
{
    public function save(User $user, AbstractFlow $flow, string $state)
    {
        \Log::debug('Context.save', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state
        ]);


        \Cache::forever($this->key($user), [
            'flow' => get_class($flow),
            'state' => $state
        ]);
    }

    public function get(User $user)
    {
        return cache($this->key($user), []);
    }

    public function key(User $user)
    {
        return 'context_' . $user->id;
    }
}