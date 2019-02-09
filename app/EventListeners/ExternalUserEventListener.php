<?php

namespace App\EventListeners;

use App\Events\External\UserCreated;
use App\Repositories\UserRepository;
use Psr\Log\LoggerInterface;

class ExternalUserEventListener extends Listener
{
    private $logger;

    private $repo;

    protected static $events = [
        UserCreated::class
    ];

    public function __construct(LoggerInterface $logger, UserRepository $repository)
    {
        $this->logger = $logger;

        $this->repo = $repository;
    }

    public function onUserCreated(UserCreated $event): void
    {
        $this->logger->info($event);

        $this->repo->createUser($event);

        // TODO: Domain event to do domain stuff
    }
}