<?php
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 06.01.18
 * Time: 14:28
 */

namespace App\Conversation\Traits;


trait HasTriggers
{
    protected $triggers = [];

    protected function addTrigger(string $value)
    {
        $this->triggers[] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getTriggers(): array
    {
        return $this->triggers;
    }

    public function hasTriggers($value): bool
    {
        return in_array($value, $this->triggers);
    }
}