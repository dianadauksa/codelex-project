<?php

namespace App;

use App\Models\User;
use App\Services\Stock\ShowAllStocksService;

class StockTransactionValidation
{
    public function buyValidation(string $stockSymbol, int $buyAmount, User $user): void
    {
        if ($buyAmount <= 0) {
            $_SESSION['errors']['amount'] = 'Amount must be positive';
        }
        $this->validateMoney($stockSymbol, $buyAmount, $user);
        $this->validateAmountOwnedForBuy($stockSymbol, $buyAmount, $user);
    }

    public function sellValidation(string $stockSymbol, int $sellAmount, User $user): void
    {
        if ($sellAmount <= 0) {
            $_SESSION['errors']['amount'] = 'Amount must be positive';
        }
        $this->validateAmountOwnedForSale($stockSymbol, $sellAmount, $user);
    }

    public function validationFailed(): bool
    {
        return count($_SESSION['errors']) > 0;
    }

    private function validateMoney(string $stockSymbol, int $stockAmount, User $user): void
    {
        if ($user->getMoney() < $stockAmount * $this->getStockPrice($stockSymbol)) {
            $_SESSION['errors']['money'] = 'Not enough money for the purchase. Money available: $' . $user->getMoney();
        }
    }

    private function validateAmountOwnedForSale(string $stockSymbol, int $stockAmount, User $user): void
    {
        $userAmountOfStock = $user->getStockBySymbol($stockSymbol)['amount'];

        if ($userAmountOfStock > 0 && $userAmountOfStock < $stockAmount) {
            $_SESSION['errors']['amount'] = 'Not enough stocks for the sale. You own: ' . $userAmountOfStock;
        }
    }

    private function validateAmountOwnedForBuy(string $stockSymbol, int $stockAmount, User $user): void
    {
        $userAmountOfStock = $user->getStockBySymbol($stockSymbol)['amount'];

        if ($userAmountOfStock < 0 && $userAmountOfStock > -$stockAmount) {
            $_SESSION['errors']['amount'] = 'Please buy the shortlisted stocks before purchasing more. You have shortlisted: ' . abs($userAmountOfStock) . 'stocks';
        }
    }

    private function getStockPrice(string $stockSymbol): float
    {
        $service = new ShowAllStocksService();
        $stock = $service->executeSingle($stockSymbol);
        return $stock->getCurrentPrice();
    }
}