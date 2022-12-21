<?php

namespace App;

use App\Models\User;

class SendStockValidation
{
    public function validationFailed(): bool
    {
        return count($_SESSION['errors']) > 0;
    }

    public function validate(User $user, int $stockAmount, string $stockSymbol): void
    {
        if ($stockAmount <= 0) {
            $_SESSION['errors']['amount'] = 'Amount must be positive';
        }
        $this->validateAmountOwned($user, $stockAmount, $stockSymbol);
    }

    private function validateAmountOwned(User $user, int $stockAmount, string $stockSymbol): void
    {
        $userAmount = $user->getStockBySymbol($stockSymbol)['amount'];

        if ($userAmount < $stockAmount || $userAmount == null) {
            $_SESSION['errors']['amount'] = 'Not enough stocks for the gift';
        }
    }
}