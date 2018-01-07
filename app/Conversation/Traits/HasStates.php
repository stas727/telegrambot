<?php
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 06.01.18
 * Time: 14:28
 */

namespace App\Conversation\Traits;


trait HasStates
{
    protected $states = [];


    protected function addStates(string $state)
    {
        $this->states[] = $state;

        return $this;
    }

    /**
     * @return array
     */
    public function getStates(): array
    {
        return $this->states;
    }

    /**
     * @param null $current
     * @return null
     */
    public function getNextState($current = null)
    {
        $states = $this->getStates();

        if (is_null($current)) {
            return $states[0];
        }
        $current = collect($this->getStates())->search(function ($item) use ($current) {
            return $item == $current;
        });
        if (isset($states[$current + 1])) {
            return $states[$current + 1];
        }
        return null;
    }
}