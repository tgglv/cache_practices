<?php declare(strict_types=1);

namespace Daemon\CacheWarmers;

use Daemon\BookRepository;

class BookCacheWarmer extends BaseCacheWarmer
{
    protected const CACHE_HASH_NAME = 'cache:hash:book';
    /** @var BookRepository */
    private $bookRepository;

    public function __construct(BookRepository $bookRepository, \Redis $redisClient)
    {
        parent::__construct($redisClient);
        $this->bookRepository = $bookRepository;
    }

    public function fetchAllEntities(): BaseCacheWarmer
    {
        $books = $this->bookRepository->getLatestBooks();
        foreach ($books as $book) {
            $this->fetchById((int)$book['id']);
        }
        return $this;
    }

    public function fetchById(int $id): array
    {
        $entity = $this->bookRepository->getBookById($id);
        $this->entityList[] = $entity;
        return $entity;
    }

    public function getKey(array $data): string
    {
        return $data['id'];
    }
}