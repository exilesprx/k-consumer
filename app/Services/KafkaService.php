<?php

namespace App\Services;

use App\Events\External\ExternalEventContract;
use App\ValueObjects\UserCreatedQueue;
use Illuminate\Contracts\Container\Container;
use Illuminate\Events\Dispatcher;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Consumer;
use Interop\Queue\Message;

class KafkaService
{
    private $connection;

    private $queue;

    private $dispatcher;

    private $container;

    public function __construct(ConnectionFactory $connection, UserCreatedQueue $queue, Dispatcher $dispatcher, Container $container)
    {
        $this->connection = $connection;

        $this->queue = $queue;

        $this->dispatcher = $dispatcher;

        $this->container = $container;
    }

    public function consume(): void
    {
        $consumer = $this->createConsumer();

        $this->process($consumer);
    }

    private function createConsumer(): Consumer
    {
        $context = $this->connection->createContext();

        $queue = $context->createQueue($this->queue);

        $consumer = $context->createConsumer($queue);

        $consumer->setCommitAsync(true);

        return $consumer;
    }

    private function process(Consumer $consumer): void
    {
        while(true) {
            $message = $consumer->receive();

            $consumer->acknowledge($message);

            $event = $this->getEvent($message);

            $this->dispatcher->dispatch($event);
        }
    }

    private function getEvent(Message $message): ExternalEventContract
    {
        $event = $message->getBody(); // TODO: Need to fix this so its not dependant on external services

        $data = $message->getProperties();

        return $this->container->make(
            $event,
            [
                'name' => array_get($data, 'name'),
                'email' => array_get($data, 'email')
            ]
        );
    }
}