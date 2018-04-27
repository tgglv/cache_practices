<?php declare(strict_types=1);

namespace Daemon\CacheWarmers;

use Daemon\BookRepository;

class CacheWarmerFactory
{
    /** @var BookRepository */
    private $bookRepository;
    /** @var \Redis */
    private $redisClient;

    public function __construct(BookRepository $bookRepository, \Redis $redisClient)
    {
        $this->bookRepository = $bookRepository;
        $this->redisClient = $redisClient;
    }

    public function getBookCacheWarmer(): BookCacheWarmer
    {
        return new BookCacheWarmer($this->bookRepository, $this->redisClient);
    }

    public function getBookCollectionCacheWarmer(): BookCollectionCacheWarmer
    {
        return new BookCollectionCacheWarmer($this->bookRepository, $this->redisClient);
    }
}