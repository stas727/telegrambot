<?php

namespace App\Providers;

use App\Conversation\Conversation;
use Illuminate\Support\ServiceProvider;

class CanversationServiceProvider extends ServiceProvider
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
        $this->app->bind(Conversation::class, function (){
           return new Conversation(config('conversation.flows'));
        });
    }
}
