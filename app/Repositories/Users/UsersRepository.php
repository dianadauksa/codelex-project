<?php

namespace App\Repositories\Users;

use App\Services\User\RegisterUserServiceRequest;

interface UsersRepository
{
    public function add(RegisterUserServiceRequest $request): void;
    public function getByEmail(string $email): ?array;
    public function getByID(int $id): ?array;
    public function getAmountOwned(int $id, string $symbol): ?int;
    public function getUserStock(int $id, string $symbol): ?array;
    public function getAllUsers(): ?array;
}