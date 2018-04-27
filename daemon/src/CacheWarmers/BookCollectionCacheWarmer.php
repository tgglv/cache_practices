<?php declare(strict_types=1);

namespace Daemon\CacheWarmers;

use Daemon\BookRepository;

class BookCollectionCacheWarmer extends BaseCacheWarmer
{
    protected const CACHE_HASH_NAME = 'cache:hash:book_collection';
    private const KEY_SINGLE = 'single';

    /** @var BookRepository */
    private $bookRepository;

    public function __construct(BookRepository $bookRepository, \Redis $redisClient)
    {
        parent::__construct($redisClient);
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function fetchById(int $id): array
    {
        throw new \Exception('Method is unsuitable');
    }

    public function fetchAllEntities(): BaseCacheWarmer
    {
        $this->entityList[] = $this->bookRepository->getLatestBooks();
        return $this;
    }

    public function getKey(array $data): string
    {
        return self::KEY_SINGLE;
    }
}