<?php

namespace App\Controllers;

use App\{Database, Redirect, View, StockTransactionValidation};
use App\Services\{Stock\ShowAllStocksService, User\UserManagementService};

class BuyStockController
{
    public function index(): View
    {
        $stockSymbol = [strtoupper($_GET['symbol'])];
        $service = new ShowAllStocksService();
        $stocks = $service->execute($stockSymbol);
        return new View("singleStock", ["stocks" => $stocks->getAllStocks()]);
    }

    public function buy(): Redirect
    {
        $validation = new StockTransactionValidation();
        $validation->buyValidation($_POST);
        if ($validation->validationFailed()) {
            return new Redirect('/stock?symbol=' . $_POST['symbol']);
        }

        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();

        //for buy - check if user already owns this stock
        $service = new UserManagementService();
        $userAmountOfStock = $service->getAmountOwned($_SESSION['auth_id'], $_POST['symbol']);
        if ($userAmountOfStock == null) {
            $queryBuilder->insert('stocks')
                ->values([
                    'user_id' => '?',
                    'symbol' => '?',
                    'amount' => '?',
                ])
                ->setParameter(0, $_SESSION['auth_id'])
                ->setParameter(1, $_POST['symbol'])
                ->setParameter(2, $_POST['amount'])
                ->executeQuery();
        } else {
            $queryBuilder->update('stocks')
                ->set('amount', 'amount + ?')
                ->where('user_id = ?')
                ->andWhere('symbol = ?')
                ->setParameter(0, $_POST['amount'])
                ->setParameter(1, $_SESSION['auth_id'])
                ->setParameter(2, $_POST['symbol'])
                ->executeQuery();
        }
        $service = new UserManagementService();
        $service->subtractMoney($_SESSION['auth_id'], $_POST['amount'] * $_POST['price']);
        return new Redirect('/portfolio');
    }

    public function sell(): Redirect
    {
        $validation = new StockTransactionValidation();
        $validation->sellValidation($_POST);
        if ($validation->validationFailed()) {
            return new Redirect('/stock?symbol=' . $_POST['symbol']);
        }

        //  check if after sale user would have any stocks left - update stocks or delete stock from portfolio if none left
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();

        $service = new UserManagementService();
        $userAmountOfStock = $service->getAmountOwned($_SESSION['auth_id'], $_POST['symbol']);
        if ($userAmountOfStock == $_POST['amount']) {
            $queryBuilder
                ->delete('stocks')
                ->where('user_id = ' . $_SESSION['auth_id'])
                ->andWhere('symbol = "' . $_POST['symbol'] . '"')
                ->executeQuery();
        } else {
            $queryBuilder
                ->update('stocks')
                ->set('amount', 'amount - ' . $_POST['amount'])
                ->where('user_id = ' . $_SESSION['auth_id'])
                ->andWhere('symbol = "' . $_POST['symbol'] . '"')
                ->executeQuery();
        }

        $service = new UserManagementService();
        $service->addMoney($_SESSION['auth_id'], $_POST['amount'] * $_POST['price']);
        return new Redirect('/');
    }
}
