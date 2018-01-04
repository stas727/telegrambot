<?php

namespace Tests\Feature\Repositories;

use App\Entities\User;
use App\Repositories\UserRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRopositoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->entity = $this->moke(User::class);
        $this->repository = new UserRepository($this->entity);
    }

    /**
     * @dataProvider userDataProvider
     */
    public function test_store($id, $firstName, $lastName, $userName)
    {
        /*$id = $this->faker()->randomNumber();
        $firstname = $this->faker()->firstName;
        $lastname = $this->faker()->lastName;
        $username = $this->faker()->userName;*/


        $values = [
            'chat_id' => $id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $userName
        ];

        $this->entity->shouldReceive('firstOrCreate')->with([
            'chat_id' => $id
        ], $values)->andReturnSelf();

        $user = $this->repository->store($id, $firstName, $lastName, $userName);

        $this->assertSame($this->entity, $user);
    }

    public function userDataProvider()
    {
        return [
            [1, 'Vasya', 'Pupkin', 'vasya.pup']
        ];
    }
}
