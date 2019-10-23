<?php

namespace Spatie\EventSourcing\EventSerializers;

use Spatie\EventSourcing\ShouldNotBeStored;

interface ImpersistentEventSerializer
{
    public function serialize(ShouldNotBeStored $event): string;

    public function deserialize(string $eventClass, string $json): ShoShouldNotBeStoreduldBeStored;
}
