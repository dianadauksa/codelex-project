<?php

namespace App\Models;

use App\Database;
use Doctrine\DBAL\Connection;

class User
{
    private string $id;
    private string $name;
    private float $money;
    private array $stocks;
    private array $transactions;
    private Connection $connection;

    public function __construct(string $id)
    {
        $this->connection = Database::getConnection();
        $this->id = $id;
        $userData = $this->setUserData();
        $this->name = $userData['name'];
        $this->money = $userData['money'];
        $this->stocks = $this->setUserStocks();
        $this->transactions = $this->setUserTransactions();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMoney(): float
    {
        return $this->money;
    }

    public function getAllStocks(): array
    {
        return $this->stocks;
    }

    public function getStockBySymbol(string $symbol): array
    {
        foreach ($this->stocks as $stock) {
            if ($stock['symbol'] === $symbol) {
                return $stock;
            }
        }
        return [];
    }

    public function getAllTransactions(): array
    {
        return $this->transactions;
    }

    public function getTransactionsBySymbol(string $symbol): array
    {
        $transactions = [];
        foreach ($this->transactions as $transaction) {
            if ($transaction['symbol'] == $symbol) {
                $transactions[] = $transaction;
            }
        }
        return $transactions;
    }

    private function setUserData(): ?array
    {
        $userData = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $this->id)
            ->fetchAssociative();
        return $userData ?: null;
    }

    private function setUserStocks(): ?array
    {
        $userStocks = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('stocks')
            ->where('user_id = ?')
            ->setParameter(0, $this->id)
            ->fetchAllAssociative();
        return $userStocks ?: null;
    }

    private function setUserTransactions(): ?array
    {
        $userTransactions = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('transactions')
            ->where('user_id = ?')
            ->setParameter(0, $this->id)
            ->fetchAllAssociative();
        return $userTransactions ?: null;
    }
}