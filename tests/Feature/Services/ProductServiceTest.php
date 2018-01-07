<?php

namespace Tests\Feature\Services;

use App\Services\ProductService;
use Schema\Client;
use Schema\Collection;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */


    public function setUp()
    {
        parent::setUp();
        $this->service = new ProductService(app(Client::class));
    }

    public function test_all()
    {
        $result = $this->service->all();
        $this->assertInstanceOf(Collection::class, $result);
    }

   /* public function test_getProductByCategoryId(){
        $result = $this->service->getProductByCategoryId();
    }*/
}
