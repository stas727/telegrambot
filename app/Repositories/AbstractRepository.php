<?php
namespace App\Repositories;

use Eloquent;
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 07.12.2017
 * Time: 12:22
 */
abstract class AbstractRepository
{
    public $entity;

    function __construct(Eloquent $model)
    {
        $this->entity = $model;
    }
}