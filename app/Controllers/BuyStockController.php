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

        $service = new UserManagementService();
        $stock = $service->getUserStock($_SESSION['auth_id'], $_POST['symbol']);
        $userAmountOfStock = $stock['amount'];
        if ($userAmountOfStock == null) {
            $queryBuilder->insert('stocks')
                ->values([
                    'user_id' => '?',
                    'symbol' => '?',
                    'amount' => '?',
                    'avg_price' => '?',
                ])
                ->setParameter(0, $_SESSION['auth_id'])
                ->setParameter(1, $_POST['symbol'])
                ->setParameter(2, $_POST['amount'])
                ->setParameter(3, $_POST['price'])
                ->executeQuery();
        } else {
            $newAvgPrice = ($stock['amount']* $stock['avg_price'] + $_POST['amount']*$_POST['price'])/($_POST['amount']+$stock['amount']);
            $queryBuilder->update('stocks')
                ->set('avg_price', '?')
                ->set('amount', 'amount + ?')
                ->where('user_id = ?')
                ->andWhere('symbol = ?')
                ->setParameter(0, $newAvgPrice)
                ->setParameter(1, $_POST['amount'])
                ->setParameter(2, $_SESSION['auth_id'])
                ->setParameter(3, $_POST['symbol'])
                ->executeQuery();
        }
        $service = new UserManagementService();
        $service->subtractMoney($_SESSION['auth_id'], $_POST['amount'] * $_POST['price']);
        $queryBuilder->insert('transactions')
            ->values([
                'user_id' => '?',
                'type' => '?',
                'symbol' => '?',
                'amount' => '?',
                'price' => '?',
                'total_sum' => '?',
                'date' => '?',
            ])
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, 'BUY')
            ->setParameter(2, $_POST['symbol'])
            ->setParameter(3, $_POST['amount'])
            ->setParameter(4, $_POST['price'])
            ->setParameter(5, $_POST['amount'] * $_POST['price'])
            ->setParameter(6, date('Y-m-d H:i:s'))
            ->executeQuery();

        $total = $_POST['amount'] * $_POST['price'];
        $_SESSION['success']['purchase'] =
            "You have successfully bought {$_POST['amount']} shares of {$_POST['symbol']} for $ {$total}";
        return new Redirect("/stock?symbol=" . $_POST['symbol']);
    }

    public function sell(): Redirect
    {
        $validation = new StockTransactionValidation();
        $validation->sellValidation($_POST);
        if ($validation->validationFailed()) {
            return new Redirect('/stock?symbol=' . $_POST['symbol']);
        }

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
        $queryBuilder->insert('transactions')
            ->values([
                'user_id' => '?',
                'type' => '?',
                'symbol' => '?',
                'amount' => '?',
                'price' => '?',
                'total_sum' => '?',
                'date' => '?',
            ])
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, 'SELL')
            ->setParameter(2, $_POST['symbol'])
            ->setParameter(3, $_POST['amount'])
            ->setParameter(4, $_POST['price'])
            ->setParameter(5, $_POST['amount'] * $_POST['price'])
            ->setParameter(6, date('Y-m-d H:i:s'))
            ->executeQuery();

        $total = $_POST['amount'] * $_POST['price'];
        $_SESSION['success']['sale'] =
            "You have successfully sold {$_POST['amount']} shares of {$_POST['symbol']} for $ {$total}";
        return new Redirect('/stock?symbol=' . $_POST['symbol']);
    }
}
