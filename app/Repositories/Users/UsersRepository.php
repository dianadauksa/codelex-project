<?php

namespace App\Repositories\Users;

use App\Services\User\RegisterUserServiceRequest;

interface UsersRepository
{
    public function add(RegisterUserServiceRequest $request): void;
    public function getByEmail(string $email): ?array;
    public function getByID(int $id): ?array;
    public function getUserStocks(int $id): ?array;
    public function getAmountOwned(string $auth_id, string $symbol): ?int;
    public function getUserStock(int $id, string $symbol): ?array;
    public function subtractMoney(string $auth_id, float $transactionPrice): void;
    public function addMoney(string $auth_id, float $transactionPrice): void;
    public function getTransactionsByStock(int $id, string $symbol): ?array;
    public function getAllTransactions(int $id): ?array;
}