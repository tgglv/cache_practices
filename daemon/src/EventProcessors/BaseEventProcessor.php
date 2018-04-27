<?php declare(strict_types=1);

namespace Daemon\EventProcessors;

use Daemon\CacheWarmers\{
    BaseCacheWarmer,
    BookCacheWarmer,
    BookCollectionCacheWarmer
};
use MySQLReplication\Event\RowEvent\TableMap;

abstract class BaseEventProcessor
{
    private const HASH_BOOK = 'cache:hash:book';
    private const HASH_BOOK_COLLECTION = 'cache:hash:book_collection';

    /** @var array */
    protected $eventInfo;
    /** @var array */
    protected $dependencies;
    /** @var BookCacheWarmer */
    protected $bookCacheWarmer;
    /** @var BookCollectionCacheWarmer */
    protected $bookCollectionCacheWarmer;

    abstract public function process(): void;

    public function __construct(BookCacheWarmer $bookCacheWarmer, BookCollectionCacheWarmer $bookCollectionCacheWarmer)
    {
        $this->bookCacheWarmer = $bookCacheWarmer;
        $this->bookCollectionCacheWarmer = $bookCollectionCacheWarmer;
    }

    public function setEventInfo(array $eventInfo): self
    {
        $this->eventInfo = $eventInfo;
        return $this;
    }

    public function setDependencies(array $dependencies): self
    {
        $this->dependencies = $dependencies;
        return $this;
    }

    /**
     * @param $hashName
     * @return BaseCacheWarmer
     * @throws \Exception
     */
    public function getWarmerBy($hashName): BaseCacheWarmer
    {
        switch ($hashName) {
            case self::HASH_BOOK:
                return $this->bookCacheWarmer;
            case self::HASH_BOOK_COLLECTION:
                return $this->bookCollectionCacheWarmer;
        }
        throw new \Exception("Cannot determine warmer for {$hashName}");
    }

    protected function getRulesFromDependencies(TableMap $tableMap): array
    {
        return $this->dependencies[$tableMap->getDatabase()][$tableMap->getTable()] ?? [];
    }
}