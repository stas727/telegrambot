<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:26
 */

namespace App\Conversation\Flow;

use App\Conversation\Traits\HasStates;
use App\Conversation\Traits\HasTriggers;
use App\Conversation\Traits\InteractsWithContext;
use App\Entities\Message;
use App\Entities\User;
use App\Exceptions\ConversationException;
use App\Traits\Loggable;

/**
 * Class AbstractFlow
 *
 * @method getNextState(string $current = null)
 * @method hasTrigger(string $value)
 * @package App\Conversation\Flow
 *
 *
 */
abstract class AbstractFlow
{
    use Loggable, InteractsWithContext;

    protected $user;

    protected $message;

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

    public function handle()
    {
        $this->log('handle', [
            'user' => $this->user->id,
            'message' => $this->message->text
        ]);
        $this->validate();
        //Search in states
        $this->log('isFlowInContext', [$this->isFlowInContext($this)]);
        if ($this->usesStates() && $this->isFlowInContext($this)) {
            $state = $this->getNextState($this->context()->getState());

            if (is_null($state)) {
                $this->clearContext();
                throw new ConversationException('Next state is not defined');
            }
            $this->runState($state);
            return true;
        }
        //Search in triggers
        if ($this->usesTriggers() && $this->hasTrigger($this->message->text)) {
            $state = $this->getNextState();
            $this->runState($state);
            return true;
        }
        return false;
    }

    public function runFlow($flow, string $state = null)
    {
        $this->clearContext();
        $flow = app($flow);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $state = $state ?? $flow->getNextState();
        $flow->runState($state);
    }

    public function runState($state)
    {
        $this->log('runState', ['user' => $this->user->id, 'message' => $this->message->text, 'state' => $state]);

        //Run provided state
        $this->setContext($this, $state, $this->context()->getOptions());
        $this->$state();
    }

    public function validate()
    {
        if (
            $this->context()->hasFlow() &&
            get_class($this->context()->getFlow()) != get_class($this)
        ) {
            throw new ConversationException('Context has another flow');
        }
    }

    private function usesStates(): bool
    {
        return in_array(HasStates::class, class_uses($this));
    }

    private function usesTriggers(): bool
    {
        return in_array(HasTriggers::class, class_uses($this));
    }
}