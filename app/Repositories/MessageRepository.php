<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 07.12.2017
 * Time: 15:31
 */

namespace App\Repositories;


use App\Entities\Message;
use App\Entities\User;

class MessageRepository extends AbstractRepository
{
    public function __construct(Message $entity)
    {
        parent::__construct($entity);
    }

    public function store($user, int $external_id, string $text)
    {
        return $this->entity->create([
            'user_id' => $user->id,
            'external_id' => $external_id,
            'text' => $text
        ]);
    }
}