<?php declare(strict_types=1);

namespace Daemon;

use Daemon\{
    CacheWarmers\CacheWarmerFactory,
    EventProcessors\EventProcessorFactory
};
use MySQLReplication\{
    Config\ConfigBuilder,
    MySQLReplicationFactory,
    Socket\Socket,
    Socket\SocketException
};

date_default_timezone_set('Asia/Yekaterinburg');
require \dirname(__DIR__) . '/vendor/autoload.php';

// TODO: вынести в Config
$host = gethostbyname('mysql');
$user = 'bookstore';
$password = 12345;
$port = 3306;

// waiting for connection
$attempts = 100;
$socket = new Socket;
$connected = false;
do {
    --$attempts;
    try {
        $socket->connectToStream($host, $port);
        $connected = true;
    } catch (SocketException $e) {
        echo "{$e->getMessage()}\n"; # TODO: заменить логированием
        sleep(5);
    }
} while (!$connected && $attempts > 0);

// Нагреем кеш после того как подключимся к MySQL
echo `php warmup.php`;

$configBuilder = (new ConfigBuilder)
    ->withHost($host)
    ->withUser($user)
    ->withPassword($password)
    ->withSlaveId(1)
    ->build();

require __DIR__ . '/common.php';
$cacheWarmerFactory = new CacheWarmerFactory($bookRepository, $redisClient);
$eventProcessorFactory = new EventProcessorFactory(
    $cacheWarmerFactory->getBookCacheWarmer(),
    $cacheWarmerFactory->getBookCollectionCacheWarmer(),
    new EntityCacheDependencyBuilder
);

$binLogStream = new MySQLReplicationFactory($configBuilder);
$binLogStream->registerSubscriber(new BookStoreSubscriber($eventProcessorFactory));
$binLogStream->run();