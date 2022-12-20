<?php

namespace App\Models;

class Friend
{
    private string $name;
    private string $email;
    private int $id;

    public function __construct(string $name, string $email, int $id)
    {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getId(): int
    {
        return $this->id;
    }
}