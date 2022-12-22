<?php

namespace App\Controllers;

use App\{Models\User, Redirect, View, StockTransactionValidation};
use App\Services\{Stock\ShowAllStocksService, UserStock\BuyStockService, UserStock\SellStockService};

class SingleStockController
{
    private ShowAllStocksService $service;
    private StockTransactionValidation $validation;
    private BuyStockService $buyService;
    private SellStockService $sellService;

    public function __construct(
        ShowAllStocksService       $service,
        StockTransactionValidation $validation,
        BuyStockService            $buyService,
        SellStockService           $sellService
    )
    {
        $this->service = $service;
        $this->validation = $validation;
        $this->buyService = $buyService;
        $this->sellService = $sellService;
    }

    public function index(): View
    {
        $stockSymbol = strtoupper($_GET['symbol']);
        //$service = new ShowAllStocksService(); uses DI
        $stock = $this->service->executeSingle($stockSymbol);

        $user = new User($_SESSION['auth_id']);
        $stockTransactions = $user->getTransactionsBySymbol($stockSymbol);

        return new View("singleStock", ["stock" => $stock, "transactions" => $stockTransactions]);
    }

    public function buy(): Redirect
    {
        $stockSymbol = $_POST['symbol'];
        $stockAmount = $_POST['amount'];
        $userId = $_SESSION['auth_id'];
        $user = new User($userId);

        //$validation = new StockTransactionValidation(); uses DI
        $this->validation->buyValidation($stockSymbol, $stockAmount, $user);
        if ($this->validation->validationFailed()) {
            return new Redirect('/stock?symbol=' . $stockSymbol);
        }

        //$service = new BuyStockService(); uses DI
        $this->buyService->execute($stockSymbol, $stockAmount, $user);
        return new Redirect("/stock?symbol=" . $stockSymbol);
    }

    public function sell(): Redirect
    {
        $stockSymbol = $_POST['symbol'];
        $stockAmount = $_POST['amount'];
        $userId = $_SESSION['auth_id'];
        $user = new User($userId);

        //$validation = new StockTransactionValidation(); uses DI
        $this->validation->sellValidation($stockSymbol, $stockAmount, $user);
        if ($this->validation->validationFailed()) {
            return new Redirect('/stock?symbol=' . $stockSymbol);
        }

        //$service = new SellStockService(); uses DI
        $this->sellService->execute($stockSymbol, $stockAmount, $user);
        return new Redirect('/stock?symbol=' . $stockSymbol);
    }
}
