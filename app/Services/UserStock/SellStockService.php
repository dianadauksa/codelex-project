<?php

namespace App\Services\UserStock;

use App\Database;
use App\Models\User;
use App\Services\Stock\ShowAllStocksService;
use Doctrine\DBAL\Connection;

class SellStockService
{
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function execute(string $stockSymbol, int $sellAmount, User $user): void
    {
        $userAmountOfStock = $user->getStockBySymbol($stockSymbol)['amount'];

        if ($userAmountOfStock == $sellAmount) {
            $this->deleteStock($stockSymbol, $user);
        } elseif ($userAmountOfStock > $sellAmount) {
            $this->updateExistingStock($stockSymbol, $sellAmount, $user);
        } /*elseif ($userAmountOfStock == null) { //also when already short and want to sell more => update
            $this->insertStock($stockSymbol, $sellAmount, $user);
        }*/

        $this->updateUserMoney($stockSymbol, $sellAmount, $user);
        $this->insertTransaction($stockSymbol, $sellAmount, $user);

        $total = $sellAmount * $this->getPrice($stockSymbol);
        $_SESSION['success']['sale'] =
            "Successfully sold $sellAmount shares of $stockSymbol for $ {$total}";

    }

    private function getPrice(string $stockSymbol): float
    {
        $service = new ShowAllStocksService();
        $stock = $service->executeSingle($stockSymbol);
        return $stock->getCurrentPrice();
    }

    private function deleteStock(string $stockSymbol, User $user): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->delete('stocks')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $user->getId())
            ->setParameter(1, $stockSymbol)
            ->executeQuery();
    }

    private function updateExistingStock(string $stockSymbol, int $sellAmount, User $user): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->update('stocks')
            ->set('amount', 'amount - ?')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $sellAmount)
            ->setParameter(1, $user->getId())
            ->setParameter(2, $stockSymbol)
            ->executeQuery();
    }

    private function updateUserMoney(string $stockSymbol, int $sellAmount, User $user): void
    {
        $moneyLeft = $user->getMoney() + $sellAmount * $this->getPrice($stockSymbol);
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->update('users')
            ->set('money', '?')
            ->where('id = ?')
            ->setParameter(0, $moneyLeft)
            ->setParameter(1, $user->getId())
            ->executeQuery();
    }

    private function insertTransaction(string $stockSymbol, int $sellAmount, User $user): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
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
            ->setParameter(1, 'SELL')
            ->setParameter(2, $stockSymbol)
            ->setParameter(3, $sellAmount)
            ->setParameter(4, $this->getPrice($stockSymbol))
            ->setParameter(5, $sellAmount * $this->getPrice($stockSymbol))
            ->setParameter(6, date('Y-m-d H:i:s'))
            ->executeQuery();
    }
}