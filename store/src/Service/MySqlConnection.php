<?php declare(strict_types=1);

namespace App\Service;

class MySqlConnection implements StorageConnectionInterface
{
    private static $link;
    /** @var \mysqli */
    private $connection;

    private function __construct(\mysqli $connection)
    {
        $this->connection = $connection;
    }

    public static function getLink(): \mysqli
    {
        if (null === self::$link) {
            $host = getenv('MYSQL_HOST');
            $user = getenv('MYSQL_USER');
            $password = getenv('MYSQL_USER_PASSWORD');

            self::$link = new MySqlConnection(mysqli_connect($host, $user, $password, 'bookstore', 3306));
            if (null !== self::$link->getConnection()) {
                mysqli_query(self::$link->getConnection(), 'SET NAMES utf8');
            }
        }
        return self::$link->getConnection();
    }

    public function getConnection(): \mysqli
    {
        return $this->connection;
    }

    public function __destruct()
    {
        if (null !== $this->connection) {
            mysqli_close($this->connection);
        }
    }
}