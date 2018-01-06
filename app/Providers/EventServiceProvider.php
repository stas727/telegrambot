<?php

namespace App\Providers;

use App\Events\FlowRunned;
use App\Events\OptionChanged;
use App\Listeners\SaveRunnedFlowToContext;
use App\Listeners\UpdateOptionInContext;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Log\Events\MessageLogged;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OptionChanged::class => [
            UpdateOptionInContext::class
        ],
        FlowRunned::class => [
            SaveRunnedFlowToContext::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \Log::listen(function (MessageLogged $e) {
            $level = $e->level;
            switch ($level) {
                case 'debug':
                    $level = 'info';
                    break;
                case 'notice':
                case 'alert':
                    $level = 'warning';
                    break;
                case 'critical':
                    $level = 'error';
            }
            $context = $e->context;
            if (isset($context['exception'])) {
                $message = $context['exception'];
                unset($context['exception']);
            } else {
                $message = $e->message;
            }
            $context['level'] = $level;
            if ($message instanceof \Exception) {
                app('sentry')->captureException($message, [], $context);
            } else {
                app('sentry')->captureMessage($message, [], $context);
            }
        });
    }
}
