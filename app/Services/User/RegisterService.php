<?php

namespace App\Services\User;

use App\Database;

class RegisterService
{
    public function execute(RegisterServiceRequest $request): void
    {
        $connection = Database::getConnection();
        $connection->insert('Users', [
            'name' => $request->getName(),
            'email' => filter_var($request->getEmail(), FILTER_SANITIZE_EMAIL),
            'password' => password_hash($request->getPassword(), PASSWORD_DEFAULT)
        ]);
    }
}