<?php

namespace Tests\Feature\Repositories;

use App\Entities\Message;
use App\Entities\User;
use App\Repositories\AbstractRepository;
use App\Repositories\MessageRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageRopositoryTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->moke(User::class);

        $this->entity = $this->moke(Message::class);

        $this->repository = new MessageRepository($this->entity);

    }

    public function test_store()
    {
        $userId = $this->faker()->randomNumber();
        $this->user->shouldReceive('getAttribute')->with('id')->andReturn($userId);
        $externalId = $this->faker()->randomNumber();
        $text = $this->faker()->text;
        $this->entity->shouldReceive('create')->with([
            'user_id' => $userId,
            'external_id' => $externalId,
            'text' => $text
        ])->andReturnSelf();
        $this->repository->store($this->user, $externalId, $text);
    }
}
