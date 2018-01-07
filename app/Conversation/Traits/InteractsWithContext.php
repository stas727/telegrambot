<?php
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 06.01.18
 * Time: 14:00
 */

namespace App\Conversation\Traits;


use App\Conversation\Context;
use App\Conversation\Flow\AbstractFlow;
use App\Entities\User;
use Cache;

trait InteractsWithContext
{
    /**
     * @var User
     */
    protected $user;

    protected function setContext(AbstractFlow $flow, string $state, array $options = [])
    {
        $value = new Context($flow, $state, $options);
        $this->save($value);
    }

    protected function clearContext()
    {
        Cache::forget($this->key());
    }

    protected function remember(string $key, string $value)
    {
        $context = $this->context();
        $context->setOption($key, $value);

        $this->save($context);
    }

    protected function forget(string $key)
    {
        $context = $this->context();
        $context->removeOption($key);

        $this->save($context);
    }

    protected function isFlowInContext(AbstractFlow $flow): bool
    {
        return get_class($this->context()->getFlow()) == get_class($flow);
    }

    protected function context(): Context
    {
        return Cache::get($this->key(), new Context());
    }

    private function save(Context $context)
    {
        Cache::forever($this->key(), $context);
    }

    private function key(): string
    {
        return 'context_' . $this->user->id;
    }
}