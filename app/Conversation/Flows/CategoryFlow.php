<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:23
 */

namespace App\Conversation\Flow;

use App\Services\CategoryService;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram;
use Log;

class CategoryFlow extends AbstractFlow
{

    protected $triggers = [];
    protected $states = ['first', 'navigate'];
    protected $options = [
        'parent_id' => null
    ];

    public function first()
    {
        /**
         * @var CategoryService $category
         */
        $parent_id = $this->options['parent_id'];


        $buttons = [];


        foreach ($this->categories() as $category) {
            if ($category->offsetGet('parent_id') == $parent_id) {
                $buttons[] = [$category->offsetGet('name')];
            }
        }

        Telegram::sendMessage([
            'chat_id' => $this->user->chat_id,
            'text' => 'Список категорий',
            'reply_markup' => Keyboard::make([
                'keyboard' => $buttons,
                'resize_keyboard' => true,
                'one_time_keyboard' => true])
        ]);
    }

    public function navigate()
    {
        $category = collect($this->categories())->first(function ($record){
            if(hash_equals($record->offsetGet('name'), $this->message->text)){
                return $record;
            }
        });
        $id = $category->getOffset('id');
        $this->options = ['parent_id' => $id];
        //$this->saveOption('parent_id', $id ?? $this->options['parent_id']);
        //$this->run('first', ['parent_id' => $id]);
        $this->first();
    }

    private function categories()
    {
        $services = app(CategoryService::class);
        return $services->all()->records();
    }
}