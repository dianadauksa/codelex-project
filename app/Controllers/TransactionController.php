<?php

namespace App\Controllers;

use App\Services\User\UserManagementService;
use App\View;

class TransactionController
{
    public function index(): View
    {
        $service = new UserManagementService();
        $transactions = $service->getAllTransactions($_SESSION['auth_id']);

        return new View("transactions", ['transactions' => $transactions]);
    }

    public function showTransactions(): View
    {
        $service = new UserManagementService();
        $transactions = $service->getTransactionsByStock($_SESSION['auth_id'], $_GET['symbol']);

        return new View("transactions", [
            'transactions' => $transactions,
            'symbol' => $_GET['symbol']]);
    }
}