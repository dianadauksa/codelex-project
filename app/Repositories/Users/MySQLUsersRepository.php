<?php

namespace App\Repositories\Users;

use App\Database;
use App\Services\User\RegisterUserServiceRequest;

class MySQLUsersRepository implements UsersRepository
{
    public function add(RegisterUserServiceRequest $request): void
    {
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->insert('users')
            ->values([
                'name' => '?',
                'email' => '?',
                'password' => '?'
            ])
            ->setParameter(0, $request->getName())
            ->setParameter(1, filter_var($request->getEmail(), FILTER_SANITIZE_EMAIL))
            ->setParameter(2, password_hash($request->getPassword(), PASSWORD_DEFAULT))
            ->executeQuery();
    }

    public function getByEmail(string $email): ?array
    {
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchAssociative();
        return $user ?: null;
    }

    public function getByID(int $id): ?array
    {
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();
        return $user ?: null;
    }

    public function getAllUsers(): ?array
    {
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $users = $queryBuilder
            ->select('*')
            ->from('users')
            ->fetchAllAssociative();
        return $users ?: [];
    }
}