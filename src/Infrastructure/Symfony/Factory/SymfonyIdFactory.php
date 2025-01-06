<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Infrastructure\Symfony\Factory;

use Symfony\Component\Uid\Uuid;
use TegCorp\SharedKernelBundle\Domain\Factory\IdFactory;

final readonly class SymfonyIdFactory implements IdFactory
{
    public function create(): string
    {
        return
            Uuid::v4()->toRfc4122()
        ;
    }
}
