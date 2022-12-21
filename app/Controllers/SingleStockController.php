<?php

namespace App\Controllers;

use App\{Models\User, Redirect, View, StockTransactionValidation};
use App\Services\{Stock\ShowAllStocksService, UserStock\BuyStockService, UserStock\SellStockService};

class SingleStockController
{
    public function index(): View
    {
        $stockSymbol = strtoupper($_GET['symbol']);
        $service = new ShowAllStocksService();
        $stock = $service->executeSingle($stockSymbol);

        $user = new User($_SESSION['auth_id']);
        $stockTransactions = $user->getTransactionsBySymbol($stockSymbol);

        return new View("singleStock", ["stock" => $stock, "transactions" => $stockTransactions]);
    }

    //TODO: short listing stock (update buy/sell service + transactionValidation)

    public function buy(): Redirect
    {
        $stockSymbol = $_POST['symbol'];
        $stockAmount = $_POST['amount'];
        $userId = $_SESSION['auth_id'];
        $user = new User($userId);

        $validation = new StockTransactionValidation();
        $validation->buyValidation($stockSymbol, $stockAmount, $user);
        if ($validation->validationFailed()) {
            return new Redirect('/stock?symbol=' . $stockSymbol);
        }

        $service = new BuyStockService();
        $service->execute($stockSymbol, $stockAmount, $user);
        return new Redirect("/stock?symbol=" . $stockSymbol);
    }

    public function sell(): Redirect
    {
        $stockSymbol = $_POST['symbol'];
        $stockAmount = $_POST['amount'];
        $userId = $_SESSION['auth_id'];
        $user = new User($userId);

        $validation = new StockTransactionValidation();
        $validation->sellValidation($stockSymbol, $stockAmount, $user);
        if ($validation->validationFailed()) {
            return new Redirect('/stock?symbol=' . $stockSymbol);
        }

        $service = new SellStockService();
        $service->execute($stockSymbol, $stockAmount, $user);
        return new Redirect('/stock?symbol=' . $stockSymbol);
    }
}
