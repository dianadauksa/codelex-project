<?php

namespace App\Controllers;

use App\Services\UserStock\ShowUserStocksService;
use App\View;

class PortfolioController
{
    public function index(): View
    {
        $service = new ShowUserStocksService();
        $userStocks = $service->execute($_SESSION['auth_id']);
        $portfolio = $userStocks->getAll();

        $totalProfit = 0;
        $totalValue = 0;
        foreach ($portfolio as $stock) {
            $totalProfit += $stock->getChange();
            $totalValue += $stock->getCurrentPrice() * $stock->getAmountOwned();
        }
        return new View("portfolio", [
            'portfolio' => $portfolio,
            'totalProfit' => $totalProfit,
            'totalValue' => $totalValue]);
    }
}
