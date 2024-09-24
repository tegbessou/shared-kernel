<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Application\EventDispatcher;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use TegCorp\SharedKernelBundle\Domain\Entity\EntityWithDomainEventInterface;
use TegCorp\SharedKernelBundle\Domain\Service\DomainEventDispatcherInterface;

final readonly class DomainEventDispatcher implements DomainEventDispatcherInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    #[\Override]
    public function dispatch(EntityWithDomainEventInterface $entity): void
    {
        try {
            foreach ($entity::getRecordedEvent() as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        } finally {
            $entity::eraseRecordedEvents();
        }
    }
}
