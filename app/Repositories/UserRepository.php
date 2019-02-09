<?php

namespace App\Repositories;

use App\Events\External\UserCreated;
use App\User;

class UserRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function createUser(UserCreated $event)
    {
        $user = new User(
            $event->toArray()
        );

        $user->save();
    }
}