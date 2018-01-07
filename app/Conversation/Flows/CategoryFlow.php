<?php
/**
 * Created by PhpStorm.
 * User: stasm
 * Date: 12.12.2017
 * Time: 16:23
 */

namespace App\Conversation\Flow;

use App\Conversation\Traits\HasOptions;
use App\Conversation\Traits\HasStates;
use App\Conversation\Traits\SendMessages;
use App\Services\CategoryService;
use Schema\Record;

class CategoryFlow extends AbstractFlow
{
    use SendMessages, HasStates, HasOptions;

    function __construct()
    {
        //States
        $this
            ->addStates('showParent')
            ->addStates('showChildren');

        //Options
        $this->
        addOption('parent_id');
    }

    public function showParent()
    {
        $parent_id = $this->getOption('parent_id');
        $buttons = [];

        foreach ($this->categories() as $category) {
            if ($category->offsetGet('parent_id') == $parent_id) {
                $buttons[] = $category->offsetGet('name');
            }
        }
        $this->reply('Список категорий ', $buttons);
    }

    public function showChildren()
    {
        $category = collect($this->categories())->first(function (Record $record) {
            return hash_equals($record->offsetGet('name'), $this->message->text);

        });
        $this->log('showChildren', ['category' => $category]);
        if (is_null($category)) {
            return;
        }
        $this->remember('parent_id', $category->offsetGet('id'));

        $this->runState('showParent');
        //$this->first();
    }

    private function categories()
    {
        $services = app(CategoryService::class);
        return $services->all()->records();
    }
}