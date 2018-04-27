<?php declare(strict_types=1);

namespace Daemon;

use Daemon\EventProcessors\{
    BaseEventProcessor,
    EventProcessorFactory
};
use MySQLReplication\Event\{
    DTO\EventDTO, EventSubscribers
};

class BookStoreSubscriber extends EventSubscribers
{
    /** @var EventProcessorFactory */
    private $eventProcessorFactory;

    public function __construct(EventProcessorFactory $eventProcessorFactory)
    {
        $this->eventProcessorFactory = $eventProcessorFactory;
    }

    /** @param EventDTO $event (your own handler more in EventSubscribers class ) */
    public function allEvents(EventDTO $event): void
    {
        $eventInfo = $event->jsonSerialize();

        /** @var string $eventType */
        $eventType = $eventInfo['type'] ?? null;
        if (null === $eventType) {
            return;
        }

        $processor = $this->eventProcessorFactory->getProcessor($eventType);
        if ($processor instanceof BaseEventProcessor) {
            $processor->setEventInfo($eventInfo)->process();
        }
    }
}