<?php

namespace App\Services\User;

use App\Repositories\Users\MySQLUsersRepository;
use App\Repositories\Users\UsersRepository;

class UserManagementService
{
    private UsersRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new MySQLUsersRepository();
    }

    public function register(RegisterUserServiceRequest $request): void
    {
        $this->usersRepository->add($request);
    }

    public function getUserByEmail(string $email): ?array
    {
        return $this->usersRepository->getByEmail($email);
    }

    public function getUserByID(int $id): ?array
    {
        return $this->usersRepository->getByID($id);
    }

    public function getUserStocks(int $id): array
    {
        return $this->usersRepository->getUserStocks($id);
    }

    public function getAmountOwned(int $id, string $symbol): ?int
    {
        return $this->usersRepository->getAmountOwned($id, $symbol);
    }

    public function getUserStock(int $id, string $symbol): ?array
    {
        return $this->usersRepository->getUserStock($id, $symbol);
    }

    public function subtractMoney(int $id, float $transactionPrice): void
    {
        $this->usersRepository->subtractMoney($id, $transactionPrice);
    }

    public function addMoney(int $id, float $transactionPrice): void
    {
        $this->usersRepository->addMoney($id, $transactionPrice);
    }

    public function getTransactionsByStock(int $id, string $symbol): array
    {
        return $this->usersRepository->getTransactionsByStock($id, $symbol);
    }

    public function getAllTransactions(int $id): array
    {
        return $this->usersRepository->getAllTransactions($id);
    }
}