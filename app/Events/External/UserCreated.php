<?php

namespace App\Events\External;

use Illuminate\Contracts\Support\Arrayable;

class UserCreated implements ExternalEventContract, Arrayable
{
    private $name;

    private $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;

        $this->email = $email;
    }

    public static function fromArray(array $parameters)
    {
        $name = array_get($parameters, 'name');

        $email = array_get($parameters, 'email');

        return new self($name, $email);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}