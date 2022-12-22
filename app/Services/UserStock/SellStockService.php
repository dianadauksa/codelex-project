<?php

namespace App\Services\UserStock;

use App\Database;
use App\Models\User;
use App\Services\Stock\ShowAllStocksService;

class SellStockService
{
    private ShowAllStocksService $service;

    public function __construct(ShowAllStocksService $service)
    {
        $this->service = $service;
    }

    public function execute(string $stockSymbol, int $sellAmount, User $user): void
    {
        $userAmountOfStock = $user->getStockBySymbol($stockSymbol)['amount'];
        $total = $sellAmount * $this->getPrice($stockSymbol);

        if ($userAmountOfStock == $sellAmount) {
            $this->deleteStock($stockSymbol, $user);
            $this->insertTransaction('SELL', $stockSymbol, $sellAmount, $user);

            $_SESSION['success']['sale'] =
                "Successfully sold $sellAmount shares of $stockSymbol for $ {$total}";
        } elseif ($userAmountOfStock > $sellAmount) {
            $this->updateExistingStock($stockSymbol, $sellAmount, $user);
            $this->insertTransaction('SELL', $stockSymbol, $sellAmount, $user);

            $_SESSION['success']['sale'] =
                "Successfully sold $sellAmount shares of $stockSymbol for $ {$total}";
        } elseif ($userAmountOfStock == null) {
            $this->insertStock($stockSymbol, $sellAmount, $user);
            $this->insertTransaction('SHORTLIST', $stockSymbol, $sellAmount, $user);

            $_SESSION['success']['short'] =
                "Successfully shortlisted $sellAmount shares of $stockSymbol for $ {$total}";
        } elseif ($userAmountOfStock < 0) {
            $this->updateShortlist($stockSymbol, $sellAmount, $user);
            $this->insertTransaction('INCREASE SHORTLIST', $stockSymbol, $sellAmount, $user);

            $_SESSION['success']['short'] =
                "Updated shortlist: $sellAmount additional shares of $stockSymbol for $ {$total}";
        }
        $this->updateUserMoney($stockSymbol, $sellAmount, $user);
    }

    private function getPrice(string $stockSymbol): float
    {
        $stock = $this->service->executeSingle($stockSymbol);
        return $stock->getCurrentPrice();
    }

    private function deleteStock(string $stockSymbol, User $user): void
    {
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->delete('stocks')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $user->getId())
            ->setParameter(1, $stockSymbol)
            ->executeQuery();
    }

    private function updateExistingStock(string $stockSymbol, int $sellAmount, User $user): void
    {
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->update('stocks')
            ->set('amount', 'amount - ?')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $sellAmount)
            ->setParameter(1, $user->getId())
            ->setParameter(2, $stockSymbol)
            ->executeQuery();
    }

    private function updateShortlist(string $stockSymbol, int $sellAmount, User $user): void
    {
        $userStock = $user->getStockBySymbol($stockSymbol);
        $newAvgPrice = ($sellAmount * $this->getPrice($stockSymbol) - $userStock['amount'] * $userStock['avg_price']) / ($sellAmount - $userStock['amount']);

        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->update('stocks')
            ->set('avg_price', '?')
            ->set('amount', 'amount - ?')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $newAvgPrice)
            ->setParameter(1, $sellAmount)
            ->setParameter(2, $user->getId())
            ->setParameter(3, $stockSymbol)
            ->executeQuery();
    }

    private function updateUserMoney(string $stockSymbol, int $sellAmount, User $user): void
    {
        $moneyLeft = $user->getMoney() + $sellAmount * $this->getPrice($stockSymbol);

        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', '?')
            ->where('id = ?')
            ->setParameter(0, $moneyLeft)
            ->setParameter(1, $user->getId())
            ->executeQuery();
    }

    private function insertTransaction(string $type, string $stockSymbol, int $sellAmount, User $user): void
    {
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
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
            ->setParameter(0, $user->getId())
            ->setParameter(1, $type)
            ->setParameter(2, $stockSymbol)
            ->setParameter(3, $sellAmount)
            ->setParameter(4, $this->getPrice($stockSymbol))
            ->setParameter(5, $sellAmount * $this->getPrice($stockSymbol))
            ->setParameter(6, date('Y-m-d H:i:s'))
            ->executeQuery();
    }

    private function insertStock(string $stockSymbol, int $sellAmount, User $user): void
    {
        $connection = Database::getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->insert('stocks')
            ->values([
                'user_id' => '?',
                'symbol' => '?',
                'amount' => '?',
                'avg_price' => '?',
            ])
            ->setParameter(0, $user->getId())
            ->setParameter(1, $stockSymbol)
            ->setParameter(2, -$sellAmount)
            ->setParameter(3, $this->getPrice($stockSymbol))
            ->executeQuery();
    }
}