<?php

namespace App\Controllers;

use App\Services\User\UserManagementService;
use App\Services\UserStock\ShowUserStocksService;
use App\View;

class PortfolioController
{
    public function index(): View
    {
        $service = new UserManagementService();
        $userStockList = $service->getUserStocks($_SESSION['auth_id']);
        $stockSymbols = [];
        foreach($userStockList as $userStock) {
            $stockSymbols[] = $userStock['symbol'];
        }

        $service = new ShowUserStocksService();
        $userStocks = $service->execute($stockSymbols);
        $portfolio = $userStocks->getAllUserStocks();
        $totalProfit = 0;
        $totalValue = 0;
        foreach($portfolio as $stock) {
            $totalProfit += $stock->getChange();
            $totalValue += $stock->getCurrentPrice() * $stock->getAmountOwned();
        }
        return new View("portfolio", [
            'portfolio' => $portfolio,
            'totalProfit' => $totalProfit,
            'totalValue' => $totalValue]);
    }
}
