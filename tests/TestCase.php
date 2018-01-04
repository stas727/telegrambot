<?php

namespace Tests;

use Faker\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function faker()
    {
        return Factory::create();
    }


    public function moke(string $class): MockInterface
    {
        $object = \Mockery::mock($class);

        $this->app->bind($class, $object);

        return $object;
    }
}
