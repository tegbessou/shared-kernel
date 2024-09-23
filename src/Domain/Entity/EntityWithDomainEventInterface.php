<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Domain\Entity;

use TegCorp\SharedKernelBundle\Domain\Event\DomainEventInterface;

interface EntityWithDomainEventInterface
{
    /**
     * @return DomainEventInterface[]
     */
    public static function getRecordedEvent(): array;

    public static function recordEvent(DomainEventInterface $event): void;

    public static function eraseRecordedEvents(): void;
}
