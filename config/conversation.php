<?php
/**
 * Created by PhpStorm.
 * User: stas727
 * Date: 06.01.18
 * Time: 15:35
 */
return [
  'flows' => [
      \App\Conversation\Flow\WelcomeFlow::class,
      \App\Conversation\Flow\CategoryFlow::class,
      \App\Conversation\Flow\ProductFlow::class
  ],
];