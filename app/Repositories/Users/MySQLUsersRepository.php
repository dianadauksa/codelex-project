<?php

namespace App\Repositories\Users;

use App\Database;
use App\Services\User\RegisterUserServiceRequest;
use Doctrine\DBAL\{Connection, Query\QueryBuilder};

class MySQLUsersRepository implements UsersRepository
{
    private Connection $connection;
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    public function add(RegisterUserServiceRequest $request): void
    {
        $this->queryBuilder->insert('users')
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
        $user = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $email)
            ->fetchAssociative();
        return $user ?: null;
    }

    public function getByID(int $id): ?array
    {
        $user = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();
        return $user ?: null;
    }

    public function getAllUsers(): ?array
    {
        $users = $this->queryBuilder
            ->select('*')
            ->from('users')
            ->fetchAllAssociative();
        return $users ?: [];
    }
}