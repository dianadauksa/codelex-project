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
        $portfolio = $service->getUserStocks($_SESSION['auth_id']);
        $stockSymbols = [];
        foreach($portfolio as $userStock) {
            $stockSymbols[] = $userStock['symbol'];
        }
        $service = new ShowUserStocksService();
        $userStocks = $service->execute($stockSymbols);
        return new View("portfolio", ['portfolio' => $userStocks->getAllUserStocks()]);
    }
}
