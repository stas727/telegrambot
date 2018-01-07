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
    /*    public static function save(User $user, AbstractFlow $flow, string $state, array $options = [])
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
        }*/
    protected $flow;
    protected $state;
    protected $options;

    public function __construct(
        AbstractFlow $flow = null,
        string $state = null,
        array $options = []
    )
    {
        $this->flow = !is_null($flow) ? get_class($flow) : null;
        $this->state = $state;
        $this->options = $options;
    }

    public function hasFlow(): bool
    {
        return !is_null($this->flow);
    }

    /**
     * @return AbstractFlow|null
     */
    public function getFlow()
    {
        return $this->flow;
    }

    public function setFlow(AbstractFlow $flow)
    {
        $this->flow = get_class($flow);
    }

    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function setOption(string $key, string $value)
    {
        $this->options[$key] = $value;
    }

    public function removeOption(string $key)
    {
        if (array_key_exists($key, $this->options)) {
            unset($this->options[$key]);
        }
    }

}