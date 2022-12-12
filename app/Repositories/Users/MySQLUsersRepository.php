<?php

namespace App\Repositories\Users;

use App\Database;
use App\Services\User\RegisterUserServiceRequest;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

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

    public function getUserStocks(int $id): ?array
    {
        $portfolio = $this->queryBuilder
            ->select('*')
            ->from('stocks')
            ->where('user_id = ?')
            ->setParameter(0, $id)
            ->fetchAllAssociative();
        return $portfolio ?: [];
    }

    public function getAmountOwned(string $auth_id, string $symbol): ?int
    {
        $amountOwned = $this->queryBuilder
            ->select('amount')
            ->from('stocks')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $auth_id)
            ->setParameter(1, $symbol)
            ->fetchOne();
        return $amountOwned ?: null;
    }

    public function subtractMoney(string $auth_id, float $transactionPrice): void
    {
        $user = $this->getByID($auth_id);
        $moneyLeft = $user['money'] - $transactionPrice;
        $this->queryBuilder->update('users')
            ->set('money', '?')
            ->where('id = ?')
            ->setParameter(0, $moneyLeft)
            ->setParameter(1, $auth_id)
            ->executeQuery();
    }

    public function addMoney(string $auth_id, float $transactionPrice): void
    {
        $user = $this->getByID($auth_id);
        $moneyLeft = $user['money'] + $transactionPrice;
        $this->queryBuilder->update('users')
            ->set('money', '?')
            ->where('id = ?')
            ->setParameter(0, $moneyLeft)
            ->setParameter(1, $auth_id)
            ->executeQuery();
    }
}