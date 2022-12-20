<?php

namespace App\Models\Collections;

use App\Models\Friend;

class FriendsCollection
{
    private array $friends = [];

    public function __construct(array $friends = [])
    {
        foreach ($friends as $friend) {
            $this->add($friend);
        }
    }

    public function add(Friend $friend): void
    {
        $this->friends[] = $friend;
    }

    public function getAllFriends(): array
    {
        return $this->friends;
    }
}