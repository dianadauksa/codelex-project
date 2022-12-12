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

    public function getAmountOwned(string $auth_id, string $symbol): ?int
    {
        return $this->usersRepository->getAmountOwned($auth_id, $symbol);
    }

    public function subtractMoney(string $auth_id, float $transactionPrice): void
    {
        $this->usersRepository->subtractMoney($auth_id, $transactionPrice);
    }

    public function addMoney(string $auth_id, float $transactionPrice): void
    {
        $this->usersRepository->addMoney($auth_id, $transactionPrice);
    }
}