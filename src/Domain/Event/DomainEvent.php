<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Domain\Event;

use Ramsey\Uuid\Uuid;
use Symfony\Contracts\EventDispatcher\Event;

abstract class DomainEvent extends Event
{
    public readonly string $id;
    public readonly int $timestamp;

    public function __construct(
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->timestamp = time();
    }
}
