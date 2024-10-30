<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Infrastructure\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use TegCorp\SharedKernelBundle\Application\Service\TransactionInterface;

final readonly class Transaction implements TransactionInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[\Override]
    public function begin(): void
    {
        $this->entityManager->beginTransaction();
    }

    #[\Override]
    public function commit(): void
    {
        $this->entityManager->commit();
    }

    #[\Override]
    public function rollback(): void
    {
        $this->entityManager->rollback();
    }
}
