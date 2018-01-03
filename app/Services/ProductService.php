<?php
namespace App\Services;

use Schema\Client;

/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 07.12.2017
 * Time: 10:06
 */
class ProductService
{
    public $api;

    //dependencies injection
    function __construct(Client $api)
    {
        $this->api = $api;
    }

    public function all()
    {
        $this->api->get('/products' , [
            'limit' => 1000
        ]);
    }
}