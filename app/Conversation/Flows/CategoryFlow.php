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
use App\Conversation\Traits\HasTriggers;
use App\Conversation\Traits\SendMessages;
use App\Services\CategoryService;
use App\Services\ProductService;
use Schema\Record;

class CategoryFlow extends AbstractFlow
{
    use SendMessages, HasStates, HasOptions, HasTriggers;

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
        /**
         * @var ProductService $product
         */
        $products = $product->productsByCategory($category->offsetGet('name'));

        if (!is_null($products)) {
            $str = '';
            foreach ($products as $product) {
                $str .= $product->offsetGet('name');
            }
            $this->reply($str);
        }
        $this->reply('no product');

        $this->remember('parent_id', $category->offsetGet('id'));

        $this->runState('showParent');
        //$this->first();
    }

    private function categories()
    {
        $services = app(CategoryService::class);
        return $services->all()->records();
    }
    public function productsByCategory($category_name){
        $services = app(CategoryService::class);
        return $services->categoryWithProducts($category_name);
    }
}