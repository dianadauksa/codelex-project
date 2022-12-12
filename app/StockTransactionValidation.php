<?php

namespace App;

use App\Services\User\UserManagementService;

class StockTransactionValidation
{
    public function buyValidation(array $post): void
    {
        if($post['amount'] <= 0) {
            $_SESSION['errors']['amount'] = 'Amount must be positive';
        }
        $this->validateMoney($post);
    }

    public function sellValidation(array $post): void
    {
        if($post['amount'] <= 0) {
            $_SESSION['errors']['amount'] = 'Amount must be positive';
        }
        $this->validateAmountOwned($post);
    }

    public function validationFailed(): bool
    {
        return count($_SESSION['errors']) > 0;
    }

    private function validateMoney(array $post): void
    {
        $service = new UserManagementService();
        $userData = $service->getUserById($_SESSION['auth_id']);

        if ($userData['money'] < $post['amount'] * $post['price']) {
            $_SESSION['errors']['money'] = 'Not enough money for the purchase';
        }
    }

    private function validateAmountOwned(array $post): void
    {
        $service = new UserManagementService();
        $userAmount = $service->getAmountOwned($_SESSION['auth_id'], $post['symbol']);

        if ($userAmount < $post['amount'] || $userAmount == null) {
            $_SESSION['errors']['amount'] = 'Not enough stocks for the sale';
        }
    }
}