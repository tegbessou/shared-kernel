<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Application\EventDispatcher;

use App\Shared\Domain\Entity\EntityWithDomainEventInterface;
use App\Shared\Domain\Service\DomainEventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

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
