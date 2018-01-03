<?php

Route::post('/telegram/' . env('TELEGRAM_BOT_ID'), [
    'as' => 'telegram.webhook',
    'uses' => 'telegram_controller@process'
]);