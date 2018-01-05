<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:23
 */

namespace App\Conversation\Flow;

use App\Services\CategoryService;
use App\Services\ProductService;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Keyboard\Button;

class CategoryFlow extends AbstractFlow
{

    protected $triggers = [];


    public function first()
    {
        /**
         * @var CategoryService $category
         */

        $services = app(CategoryService::class);
        $categories = $services->all()->records();

        $buttons = [];


        foreach ($categories as $category) {
            $buttons[] = [$category->name];
            $keyboard = [Button::make([
                'text' => $category->name
            ])];
        }

        \Telegram::sendMessage([
            'chat_id' => $this->user->chat_id,
            'text' => 'Список категорий',
            'reply_markup' => Keyboard::make([
                'keyboard' => $buttons,
                'resize_keyboard' => true,
                'one_time_keyboard' => true])
        ]);


    }
}