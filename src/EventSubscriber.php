<?php

namespace Spatie\EventSourcing;

final class EventSubscriber
{
    /** @var \Spatie\EventSourcing\StoredEventRepository */
    private $repository;

    public function __construct(string $storedEventRepository)
    {
        $this->repository = app($storedEventRepository);
    }

    public function subscribe($events): void
    {
        $events->listen('*', static::class.'@handle');
    }

    public function handle(string $eventName, $payload): void
    {
        if($this->shoudNotBeStored($eventName)) {
            $this->handleEventWoStoring($payload[0]);
        } else if($this->shouldBeStored($eventName)) {
            $this->storeEvent($payload[0]);
        } else {
            return;
        }
    }

    public function storeEvent(ShouldBeStored $event): void
    {
        $storedEvent = $this->repository->persist($event);
        $storedEvent->handle();
    }

    private function shouldBeStored($event): bool
    {
        if (! class_exists($event)) {
            return false;
        }

        return is_subclass_of($event, ShouldBeStored::class);
    }
    
    public function handleEventWoStoring(ShouldNotBeStored $event): void
    {
        $storedEvent = $this->repository->createStoredEventModel($event);
        $storedEvent->handle();
    }
    
    private function shoudNotBeStored($event): bool {
        if (! class_exists($event)) {
            return false;
        }
    
        return is_subclass_of($event, ShouldNotBeStored::class);
    }
}
