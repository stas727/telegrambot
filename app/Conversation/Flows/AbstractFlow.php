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
use App\Events\FlowRunned;
use App\Events\OptionChanged;
use Log;
use Psr\Log\InvalidArgumentException;
use Telegram;

abstract class AbstractFlow
{

    protected $user;

    protected $message;

    protected $triggers = [];

    protected $states = ['first'];

    protected $options = [];

    protected $context = [];

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

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function getStates(): array
    {
        return $this->states;
    }

    /**
     * @param string|null $state
     * @param array $options
     * @return null|string $state
     */
    public function run($state = null, $options = [])
    {
        Log::debug(
            static::class . '.run', [
                'user' => $this->user->toArray(),
                'message' => $this->message->toArray(),
                'state' => $state
            ]
        );

        //в контексте указан другой flow

        if (isset($this->context['flow']) && $this->context['flow'] != get_class($this)) {
            return false;
        }

        //перезаписываем опции из контекста
        if (count($options) > 0) {
            $this->options = array_merge($options, $this->options);
        } else {
            $this->options = array_merge($this->context['options'] ?? $this->options, $this->options);
        }
        //передано значение state
        if (!is_null($state)) {
            event(new FlowRunned($this->user, $this, $state, $this->options));
            $this->$state();
            return true;
        }
        //поиск по контексту
        $state = $this->findByContext();

        if (!is_null($state)) {
            event(new FlowRunned($this->user, $this, $state, $this->options));
            $this->$state();
            return true;
        }

        //поиск по тригерам
        $state = $this->findByTrigger();

        if (!is_null($state)) {
            event(new FlowRunned($this->user, $this, $state, $this->options));
            $this->$state();
            return true;
        }

        return false;

    }

    private function findByContext()
    {
        $state = null;
        if (isset($this->context['flow'])
            && isset($this->context['state'])
            && class_exists($this->context['flow'])
            && method_exists(app($this->context['flow']), $this->context['state'])
        ) {
            $flow = $this->getFlow($this->context['flow']);
            $states = $flow->getStates();
            $currentState = collect($states)->search($this->context['state']);
            $currentState = $states[$currentState];

            $nextState = $currentState + 1;

            if (isset($states[$nextState])) {
                $flow->run($states[$nextState]);
                return $states[$nextState];
            }
        }

        return null;
    }

    private function findByTrigger()
    {
        $state = null;
        foreach ($this->triggers as $trigger) {
            if (hash_equals($trigger, $this->message->text)) {
                $state = 'first';
            }
        }
        return $state;
    }

    protected function jump($flow, string $state = 'first')
    {
        $this->getFlow($flow)->run($state);
    }

    public function saveOption(string $key, $value)
    {
        event(new OptionChanged($this->user, $key, $value));
    }

    private function getFlow(string $flow)
    {
        if (!class_exists($flow)) {
            throw new InvalidArgumentException('Flow does not exist');
        }
        /**
         * @var AbstractFlow
         */
        $flow = app($flow);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $flow->setContext($this->context);

        return $flow;
    }

    protected abstract function first();

    public function telegram()
    {
        return Telegram::bot();
    }

}