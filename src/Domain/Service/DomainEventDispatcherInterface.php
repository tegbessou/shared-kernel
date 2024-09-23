<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Domain\Service;

use TegCorp\SharedKernelBundle\Domain\Entity\EntityWithDomainEventInterface;

interface DomainEventDispatcherInterface
{
    public function dispatch(EntityWithDomainEventInterface $entity): void;
}
