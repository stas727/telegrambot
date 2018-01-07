<?php

namespace App\Traits;
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 06.01.18
 * Time: 13:52
 */
use Log;

trait Loggable
{
    protected function log(string $message, $context = null)
    {
        $class = (new \ReflectionClass($this))->getShortName();
        $message = $class . '.' . $message;

        Log::debug($message, $context);
    }
}