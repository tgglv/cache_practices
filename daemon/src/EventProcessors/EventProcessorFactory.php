<?php declare(strict_types=1);

namespace Daemon\EventProcessors;

use Daemon\CacheWarmers\{
    BookCacheWarmer,
    BookCollectionCacheWarmer
};
use Daemon\EntityCacheDependencyBuilder;

class EventProcessorFactory
{
    private const EVENT_TYPE_INSERT = 'write';
    private const EVENT_TYPE_UPDATE = 'update';
    private const EVENT_TYPE_DELETE = 'delete';

    /** @var BaseEventProcessor[] */
    private $processors;
    /** @var EntityCacheDependencyBuilder */
    private $dependencyBuilder;
    /** @var BookCacheWarmer */
    private $bookCacheWarmer;
    /** @var BookCollectionCacheWarmer */
    private $bookCollectionCacheWarmer;

    public function __construct(
        BookCacheWarmer $bookCacheWarmer,
        BookCollectionCacheWarmer $bookCollectionCacheWarmer,
        EntityCacheDependencyBuilder $dependencyBuilder
    ) {
        $this->bookCacheWarmer = $bookCacheWarmer;
        $this->bookCollectionCacheWarmer = $bookCollectionCacheWarmer;
        $this->dependencyBuilder = $dependencyBuilder;
    }

    /**
     * @param string $eventType
     * @return BaseEventProcessor|null
     * @throws \Exception
     */
    public function getProcessor(string $eventType): ?BaseEventProcessor
    {
        if (!empty($this->processors[$eventType])) {
            return $this->processors[$eventType];
        }

        $processor = $this->getAvailableProcessor($eventType);
        if (null !== $processor) {
            $dependencies = $this->dependencyBuilder->build();
            $this->processors[$eventType] = $processor->setDependencies($dependencies);
        }

        return $processor;
    }

    private function getAvailableProcessor(string $eventType)
    {
        switch ($eventType) {
            case self::EVENT_TYPE_INSERT:
                return new InsertEventProcessor($this->bookCacheWarmer, $this->bookCollectionCacheWarmer);
            case self::EVENT_TYPE_UPDATE:
                return new UpdateEventProcessor($this->bookCacheWarmer, $this->bookCollectionCacheWarmer);
            case self::EVENT_TYPE_DELETE:
                return new DeleteEventProcessor($this->bookCacheWarmer, $this->bookCollectionCacheWarmer);
        }
        return null; // skip other types
    }
}