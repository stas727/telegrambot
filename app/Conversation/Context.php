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
use Log;
use Cache;
class Context
{
    public static function save(User $user, AbstractFlow $flow, string $state, array $options = [])
    {
        Log::debug('Context.save', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state,
            'options' => $options
        ]);


        \Cache::forever(self::key($user), [
            'flow' => get_class($flow),
            'state' => $state,
            'options' => $options
        ]);
    }

    public static function update(User $user, array $options = [])
    {

        $currentContext = self::get($user);

        Log::debug('Context.save', [
            'user' => $user->toArray(),
            'options' => $options
        ]);
        Cache::forever(self::key($user), [
            'flow' => $currentContext['flow'],
            'state' => $currentContext['state'],
            'options' => $options
        ]);
    }

    public static function get(User $user)
    {
        return Cache::get(self::key($user), []);
    }

    public static function key(User $user)
    {
        return 'context_' . $user->id;
    }
}