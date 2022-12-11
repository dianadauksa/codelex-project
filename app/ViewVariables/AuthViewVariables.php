<?php

namespace App\ViewVariables;

use App\Services\User\UserManagementService;

class AuthViewVariables implements ViewVariables
{
    public function getName(): string
    {
        return 'auth';
    }

    public function getValue(): array
    {
        if (!isset($_SESSION['auth_id'])) {
            return [];
        }
        $service = new UserManagementService();
        $user = $service->getUserById($_SESSION['auth_id']);

        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'money' => $user['money']
        ];
    }
}