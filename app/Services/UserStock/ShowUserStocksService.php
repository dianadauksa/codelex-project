<?php

namespace App\Services\UserStock;

use App\Database;
use App\Models\Collections\UserStocksCollection;
use App\Repositories\UserStocks\{UserStocksRepository, FinnhubAPIUserStocksRepository};

class ShowUserStocksService
{
    private UserStocksRepository $stocksRepository;
    public function __construct(UserStocksRepository $repository)
    {
        $this->stocksRepository = $repository;
    }

    public function execute(int $id): UserStocksCollection
    {
        $stockSymbols = [];
        $userStocks = $this->getUserStocks($id);
        foreach ($userStocks as $userStock) {
            $stockSymbols[] = $userStock['symbol'];
        }
        return $this->stocksRepository->getAll($stockSymbols);
    }

    private function getUserStocks(int $id): array
    {
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $userStocks = $queryBuilder
            ->select('*')
            ->from('stocks')
            ->where('user_id = ?')
            ->setParameter(0, $id)
            ->fetchAllAssociative();
        return $userStocks ?: [];
    }
}