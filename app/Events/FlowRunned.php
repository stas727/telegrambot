<?php

namespace App\Events;

use App\Conversation\Flow\AbstractFlow;
use App\Entities\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FlowRunned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $flow;

    protected $state;

    protected $user;

    protected $options;

    public function __construct(User $user, AbstractFlow $flow, string $state, array $options = [])
    {
        $this->user = $user;
        $this->flow = $flow;
        $this->state = $state;
        $this->options = $options;
    }

    /**
     * @return AbstractFlow
     */
    public function getFlow(): AbstractFlow
    {
        return $this->flow;
    }

    /**
     * @param AbstractFlow $flow
     */
    public function setFlow(AbstractFlow $flow)
    {
        $this->flow = $flow;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }


}
