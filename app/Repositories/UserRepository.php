<?php
namespace App\Repositories;

use App\Entities\User;

/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 07.12.2017
 * Time: 12:19
 */
class UserRepository extends AbstractRepository
{

    function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function store(int $id, string $firstname, string $lastname, string $username)
    {
        $values = [
            'chat_id' => $id,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'username' => $username
        ];
        return $this->entity->firstOrCreate(['chat_id' => $id], $values);
    }
}