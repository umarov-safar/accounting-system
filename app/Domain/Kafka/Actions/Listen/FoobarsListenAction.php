<?php

namespace App\Domain\Kafka\Actions\Listen;

use RdKafka\Message;

class FoobarsListenAction
{
    public function execute(Message $message): void
    {
        dump($message);
    }
}