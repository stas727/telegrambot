<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Entities\User;

class OptionChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $key;

    protected $value;

    protected $user;

    public function __construct(User $user, string $key, $value)
    {
        $this->key = $key;

        $this->value = $value;

        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }


}
