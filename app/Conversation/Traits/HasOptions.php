<?php
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 06.01.18
 * Time: 14:29
 */

namespace App\Conversation\Traits;

use App\Conversation\Context;

/**
 * Class HasOptions
 * @package App\Conversation\Traits
 * @method Context context()
 */
trait HasOptions
{

    protected $options = [];

    protected function addOption(string $name, string $default = null)
    {
        $this->options[] = [
            'name' => $name,
            'default' => $default
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    protected function getOption(string $name)
    {
        $option = collect($this->options)->first(function ($item) use ($name){
           return $item['name'] == $name;
        });

        $context = $this->context();

        if(isset($context->getOptions()[$name])){
            return $context->getOptions()[$name];
        }
        return $option['default'];
    }

}