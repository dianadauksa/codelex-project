<?php

namespace App;

use App\Repositories\Users\MySQLUsersRepository;
use App\Repositories\Users\UsersRepository;

class SendStockValidation
{
    private UsersRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new MySQLUsersRepository();
    }

    public function validationFailed(): bool
    {
        return count($_SESSION['errors']) > 0;
    }

    public function validate(array $post): void
    {
        if ($post['amount'] <= 0) {
            $_SESSION['errors']['amount'] = 'Amount must be positive';
        }
        $this->validateAmountOwned($post);
    }

    private function validateAmountOwned(array $post): void
    {
        $userAmount = $this->usersRepository->getAmountOwned($_SESSION['auth_id'], $post['symbol']);

        if ($userAmount < $post['amount'] || $userAmount == null) {
            $_SESSION['errors']['amount'] = 'Not enough stocks for the gift';
        }
    }
}