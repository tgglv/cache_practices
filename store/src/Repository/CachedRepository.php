<?php declare(strict_types=1);

namespace App\Repository;

use App\Service\RedisConnection;

class CachedRepository extends BookRepository
{
    private const CACHE_HASH_BOOK = 'cache:hash:book';
    private const CACHE_HASH_BOOK_COLLECTION = 'cache:hash:book_collection';

    private const SINGLE_KEY = 'single';

    public function getBookById(int $id): array
    {
        $json = RedisConnection::getInstance()->hGet(self::CACHE_HASH_BOOK, (string)$id);
        if (empty($json)) {
            $dbResult = parent::getBookById($id);
            RedisConnection::getInstance()->hSet(self::CACHE_HASH_BOOK, (string)$id, serialize($dbResult));
            return $dbResult;
        }
        return unserialize($json, ['allowed_classes' => false]);
    }

    public function getLatestBooks(): array
    {
        $json = RedisConnection::getInstance()->hGet(self::CACHE_HASH_BOOK_COLLECTION, self::SINGLE_KEY);
        if (empty($json)) {
            $dbResult = parent::getLatestBooks();
            RedisConnection::getInstance()->hSet(self::CACHE_HASH_BOOK_COLLECTION, self::SINGLE_KEY, serialize($dbResult));
            return $dbResult;
        }
        return unserialize($json, ['allowed_classes' => false]);
    }
}