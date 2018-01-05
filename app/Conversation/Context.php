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
    public static function save(User $user, AbstractFlow $flow, string $state)
    {
        \Log::debug('Context.save', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state
        ]);


        \Cache::forever(self::key($user), [
            'flow' => get_class($flow),
            'state' => $state
        ]);
    }

    public static function get(User $user)
    {
        return cache(self::key($user), []);
    }

    public static function key(User $user)
    {
        return 'context_' . $user->id;
    }
}