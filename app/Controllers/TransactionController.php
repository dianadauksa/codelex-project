<?php

namespace App\Controllers;

use App\Services\User\UserManagementService;
use App\View;

class TransactionController
{
    public function index(): View
    {
        $portfolio = $this->getPortfolio();
        $service = new UserManagementService();
        $transactions = $service->getAllTransactions($_SESSION['auth_id']);

        return new View("transactions", ['transactions' => $transactions, 'portfolio' => $portfolio]);
    }

    public function showTransactions(): View
    {
        $portfolio = $this->getPortfolio();
        $service = new UserManagementService();
        $transactions = $service->getTransactionsByStock($_SESSION['auth_id'], $_GET['symbol']);

        return new View("transactions", [
            'portfolio' => $portfolio,
            'transactions' => $transactions,
            'symbol' => $_GET['symbol']]);
    }

    private function getPortfolio(): array
    {
        $service = new UserManagementService();
        $transactions = $service->getAllTransactions($_SESSION['auth_id']);
        $portfolio = [];
        foreach ($transactions as $transaction) {
            if (!in_array($transaction['symbol'], $portfolio)) {
                $portfolio [] = $transaction['symbol'];
            }
        }
        return $portfolio;
    }
}