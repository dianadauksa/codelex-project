<?php

namespace App\Services\Friends;

use App\Database;
use App\Repositories\Users\MySQLUsersRepository;
use App\Repositories\Users\UsersRepository;
use Doctrine\DBAL\Connection;

class SendStockService
{
    private UsersRepository $usersRepository;
    private Connection $connection;

    public function __construct()
    {
        $this->usersRepository = new MySQLUsersRepository();
        $this->connection = Database::getConnection();
    }

    public function execute(array $post): void
    {
        $this->addStockToFriend($post);
        $this->updateUserStocks($post);
        $this->insertFriendTransaction($post);
        $this->insertUserTransaction($post);
        $_SESSION['success']['gift'] =
            "Successfully gifted {$post['amount']} shares of {$post['symbol']} to";
    }

    private function addStockToFriend(array $post): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $userStock = $this->usersRepository->getUserStock($_SESSION['auth_id'], strtoupper($post['symbol']));
        $friendStock = $this->usersRepository->getUserStock($post['friend_id'], strtoupper($post['symbol']));
        $friendAmountOfStock = $friendStock['amount'];
        if ($friendAmountOfStock == null) {
            $queryBuilder->insert('stocks')
                ->values([
                    'user_id' => '?',
                    'symbol' => '?',
                    'amount' => '?',
                    'avg_price' => '?',
                ])
                ->setParameter(0, $post['friend_id'])
                ->setParameter(1, strtoupper($post['symbol']))
                ->setParameter(2, $post['amount'])
                ->setParameter(3, $userStock['avg_price']) // or current stock price?
                ->executeQuery();
        } else {
            $queryBuilder->update('stocks')
                ->set('amount', 'amount + ?')
                ->where('user_id = ?')
                ->andWhere('symbol = ?')
                ->setParameter(0, $post['amount'])
                ->setParameter(1, $post['friend_id'])
                ->setParameter(2, strtoupper($post['symbol']))
                ->executeQuery();
        }
    }

    private function updateUserStocks(array $post): void
    {
        $userStock = $this->usersRepository->getUserStock($_SESSION['auth_id'], strtoupper($post['symbol']));
        $newAmount = $userStock['amount'] - $post['amount'];
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->update('stocks')
            ->set('amount', '?')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $newAmount)
            ->setParameter(1, $_SESSION['auth_id'])
            ->setParameter(2, strtoupper($post['symbol']))
            ->executeQuery();
    }

    private function insertFriendTransaction(array $post): void
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
            ->setParameter(0, $post['friend_id'])
            ->setParameter(1, 'RECEIVED GIFT')
            ->setParameter(2, strtoupper($post['symbol']))
            ->setParameter(3, $post['amount'])
            ->setParameter(4, 0.00)
            ->setParameter(5, 0.00)
            ->setParameter(6, date('Y-m-d H:i:s'))
            ->executeQuery();
    }

    private function insertUserTransaction(array $post): void
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
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, 'SEND GIFT')
            ->setParameter(2, strtoupper($post['symbol']))
            ->setParameter(3, $post['amount'])
            ->setParameter(4, 0.00)
            ->setParameter(5, 0.00)
            ->setParameter(6, date('Y-m-d H:i:s'))
            ->executeQuery();
    }
}