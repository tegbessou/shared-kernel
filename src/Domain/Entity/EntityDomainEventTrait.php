<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Domain\Entity;

use TegCorp\SharedKernelBundle\Domain\Event\DomainEventInterface;

trait EntityDomainEventTrait
{
    private static array $recordedEvents = [];

    #[\Override]
    public static function getRecordedEvent(): array
    {
        return self::$recordedEvents;
    }

    #[\Override]
    public static function recordEvent(DomainEventInterface $event): void
    {
        self::$recordedEvents[] = $event;
    }

    #[\Override]
    public static function eraseRecordedEvents(): void
    {
        self::$recordedEvents = [];
    }
}
