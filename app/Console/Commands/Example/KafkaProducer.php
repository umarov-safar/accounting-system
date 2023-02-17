<?php

namespace App\Console\Commands\Example;

use Ensi\LaravelPhpRdKafkaProducer\HighLevelProducer;
use Illuminate\Console\Command;

class KafkaProducer extends Command
{
    protected $signature = 'example:kafka-producer
                            {--topic= : key of topic from kafka.topics}';
    protected $description = 'Produce message to kafka topic. Just pipe text to command: echo banana | art example:kafka-producer --topic foobars';

    public function handle(): int
    {
        $topicKey = $this->option('topic');
        $message = $this->readStdin();

        (new HighLevelProducer($topicKey))->sendOne($message);
        return self::SUCCESS;
    }

    private function readStdin(): ?string
    {
        $piped_input = null;
        while ($line = fgets(STDIN)) {
            $piped_input .= $line;
        }

        return (string) $piped_input;
    }
}