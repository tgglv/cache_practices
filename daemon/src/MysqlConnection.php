<?php declare(strict_types=1);

namespace Daemon;

final class MysqlConnection
{
    private static $connection;

    private $link;

    private function __construct()
    {
        $host = getenv('MYSQL_HOST');
        $user = getenv('MYSQL_USER');
        $password = getenv('MYSQL_USER_PASSWORD');
        // TODO: вынести port в переменные окружения

        $attemptsLeft = 100;
        $connected = false;
        do {
            $this->link = mysqli_connect($host, $user, $password, 'bookstore', 3306);
            if (null !== $this->link && false !== $this->link) {
                mysqli_query($this->link, 'SET NAMES utf8');
                $connected = true;
            }
            if (!$connected) {
                --$attemptsLeft;
                sleep(10);
            }
        } while (!$connected && $attemptsLeft > 0);
    }

    public static function getInstance(): MysqlConnection
    {
        if (null === self::$connection) {
            self::$connection = new MysqlConnection;
        }
        return self::$connection;
    }

    public function getLink(): \mysqli
    {
        return $this->link;
    }

    public function __destruct()
    {
        if (null !== $this->link) {
            mysqli_close($this->link);
        }
    }
}