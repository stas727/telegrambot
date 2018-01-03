<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema\Client;

class SchemaIOServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function () {

            $config = config('services.schema_io');

            return new Client($config['id'], $config['secret']);
        });
    }
}
