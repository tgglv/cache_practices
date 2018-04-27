<?php declare(strict_types=1);

namespace Daemon;

$bookRepository = new BookRepository();

// TODO: переделать на RedisConnection
$host = getenv('REDIS_HOST');
$redisClient = new \Redis();
$redisClient->connect($host);