<?php

namespace App\Repositories\Users;

use App\Services\User\RegisterUserServiceRequest;

interface UsersRepository
{
    public function add(RegisterUserServiceRequest $request): void;
    public function getByEmail(string $email): ?array;
    public function getByID(int $id): ?array;
    public function getUserStocks(int $id): ?array;
    public function getAmountOwned(int $id, string $symbol): ?int;
    public function getUserStock(int $id, string $symbol): ?array;
    public function subtractMoney(int $id, float $transactionPrice): void;
    public function addMoney(int $id, float $transactionPrice): void;
    public function getTransactionsByStock(int $id, string $symbol): ?array;
    public function getAllTransactions(int $id): ?array;
    public function getAllUsers(): ?array;
}