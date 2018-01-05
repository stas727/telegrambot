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
use Log;
use Psr\Log\InvalidArgumentException;

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
     * @var array $context
     */
    protected $context = ['first'];

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
        //передано значение state
        if (!is_null($state)) {
            $this->$state();
            event(new FlowRunned($this->user, $this, $state));
            return true;
        }
        //поиск по контексту
        $state = $this->findByContext();

        if (!is_null($state)) {
            $this->$state();
            event(new FlowRunned($this->user, $this, $state));
            return true;
        }

        //поиск по тригерам
        $state = $this->findByTrigger();

        if (!is_null($state)) {
            $this->$state();
            event(new FlowRunned($this->user, $this, $state));
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

    protected function jump($flow, string $state = null)
    {
        $this->getFlow($flow)->run($state);
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

}