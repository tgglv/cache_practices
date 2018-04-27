<?php declare(strict_types=1);

namespace App\Service;

class RedisConnection implements StorageConnectionInterface
{
    /** @var \Redis */
    private static $redisClient;
    /** @var \Redis */
    private $connection;

    private function __construct(\Redis $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection(): \Redis
    {
        return $this->connection;
    }

    public static function getInstance(): \Redis
    {
        if (null === self::$redisClient) {
            $host = getenv('REDIS_HOST');
            $redis = new \Redis;
            $redis->connect($host);
            self::$redisClient = new RedisConnection($redis);
        }
        return self::$redisClient->getConnection();
    }

    public function __destruct()
    {
        if (null !== $this->connection) {
            $this->connection->close();
        }
    }
}