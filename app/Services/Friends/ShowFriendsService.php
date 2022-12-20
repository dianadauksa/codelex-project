<?php

namespace App\Services\Friends;

use App\Models\Collections\FriendsCollection;
use App\Models\Friend;
use App\Repositories\Users\MySQLUsersRepository;
use App\Repositories\Users\UsersRepository;

class ShowFriendsService
{
    private UsersRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new MySQLUsersRepository();
    }

    public function execute(): array
    {
        $users = $this->usersRepository->getAllUsers();
        $friends = new FriendsCollection();
        foreach ($users as $user) {
            if ($user['id'] !== $_SESSION['auth_id']) {
                $friends->add(new Friend($user['name'], $user['email'], $user['id']));
            }
        }
        return $friends->getAllFriends();
    }

    public function executeSingle(int $id): Friend
    {
        $user = $this->usersRepository->getByID($id);
        return new Friend($user['name'], $user['email'], $user['id']);
    }
}