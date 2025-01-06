<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Domain\Factory;

interface IdFactory
{
    public function create(): string;
}
