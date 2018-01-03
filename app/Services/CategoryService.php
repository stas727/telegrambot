<?php

namespace App\Services;

use Schema\Client;


/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 07.12.2017
 * Time: 10:05
 */
class CategoryService
{

    public $api;

    //dependencies injection
    function __construct(Client $api)
    {
        $this->api = $api;
    }

    public function all()
    {
        $this->api->get('/categories', [
            'limit' => 1000
        ]);
    }
}