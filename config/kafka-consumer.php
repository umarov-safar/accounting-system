<?php

use Ensi\LaravelInitialEventPropagation\RdKafkaConsumerMiddleware;

return [
    /*
    | Optional, defaults to empty array.
    | Array of global middleware fully qualified class names.
    */
    'global_middleware' => [ RdKafkaConsumerMiddleware::class ],

    'processors' => [],

    'consumer_options' => [
       /** options for consumer with name `default` */
       'default' => [
          /*
          | Optional, defaults to 20000.
          | Kafka consume timeout in milliseconds.
          */
         'consume_timeout' => 20000,

          /*
          | Optional, defaults to empty array.
          | Array of middleware fully qualified class names for this specific consumer.
          */
         'middleware' => [],
       ],
    ],
 ];
