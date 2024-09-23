<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Application\Command;

interface CommandBusInterface
{
    /**
     * @template T
     *
     * @param CommandInterface<T> $command
     *
     * @return T
     */
    public function dispatch(CommandInterface $command): mixed;
}
