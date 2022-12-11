<?php

namespace App\Controllers;

use App\Services\User\UserManagementService;
use App\View;

class PortfolioController
{
    public function index(): View
    {
        $service = new UserManagementService();
        $portfolio = $service->getUserStocks($_SESSION['auth_id']);
        return new View("myStocks", ['portfolio' => $portfolio]);
    }
}

/* array with 2 arrays
[["id"=> "1", "symbol"=> "TSLA", "amount"=> "5", "user_id"=> "1"],
    ["id"=> "2", "symbol"=> "AAPL", "amount"=> "2", "user_id"=> "1"]] */