<?php declare(strict_types=1);

namespace Daemon\CacheWarmers;

abstract class BaseCacheWarmer implements \Iterator
{
    protected const CACHE_HASH_NAME = '';

    /** @var int */
    private $position;
    /** @var array */
    protected $entityList;
    /** @var \Redis */
    private $redisClient;

    abstract public function getKey(array $data): string;

    abstract public function fetchById(int $id): array;

    abstract public function fetchAllEntities(): BaseCacheWarmer;

    public function __construct(\Redis $redisClient)
    {
        $this->rewind();
        $this->redisClient = $redisClient;
        $this->entityList = [];
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->entityList[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->entityList[$this->position]);
    }

    public function warm(string $key, $entity): void
    {
        $this->redisClient->hSet(static::CACHE_HASH_NAME, $key, serialize($entity));
    }

    public function delete(string $key): void
    {
        $this->redisClient->hDel(static::CACHE_HASH_NAME, [$key]);
    }

    public function getEntities()
    {
        return $this->entityList;
    }

    public function clean()
    {
        $this->entityList = [];
    }
}