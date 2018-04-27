<?php declare(strict_types=1);

namespace Daemon;

use Daemon\CacheWarmers\{
    BaseCacheWarmer,
    CacheWarmerFactory
};

date_default_timezone_set('Asia/Yekaterinburg');
require \dirname(__DIR__) . '/vendor/autoload.php';

require __DIR__ . '/common.php';
$cacheWarmerFactory = new CacheWarmerFactory($bookRepository, $redisClient);

/** @var BaseCacheWarmer[] $cacheWarmerCollection */
$cacheWarmerCollection = [
    $cacheWarmerFactory->getBookCacheWarmer()->fetchAllEntities(),
    $cacheWarmerFactory->getBookCollectionCacheWarmer()->fetchAllEntities()
];

foreach ($cacheWarmerCollection as $cacheWarmer) {
    foreach ($cacheWarmer as $entity) {
        $cacheWarmer->warm($cacheWarmer->getKey($entity), $entity);
    }
}