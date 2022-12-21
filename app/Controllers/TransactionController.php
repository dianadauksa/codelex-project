<?php

namespace App\Controllers;

use App\Models\User;
use App\View;

class TransactionController
{
    public function index(): View
    {
        $user = new User($_SESSION['auth_id']);
        $transactions = $user->getAllTransactions();

        return new View("transactions", ['transactions' => $transactions]);
    }

    public function showTransactionsForStock(): View
    {
        $user = new User($_SESSION['auth_id']);
        $transactions = $user->getTransactionsBySymbol($_GET['symbol']);

        return new View("transactions", ['transactions' => $transactions]);
    }
}