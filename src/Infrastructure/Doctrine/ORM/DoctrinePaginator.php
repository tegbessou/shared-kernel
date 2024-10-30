<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Infrastructure\Doctrine\ORM;

use Doctrine\ORM\Tools\Pagination\Paginator;
use TegCorp\SharedKernelBundle\Domain\Repository\PaginatorInterface;

/**
 * @template T of object
 *
 * @implements PaginatorInterface<T>
 */
final readonly class DoctrinePaginator implements PaginatorInterface
{
    private int $firstResult;
    private int $maxResults;

    /**
     * @param Paginator<T> $paginator
     */
    public function __construct(
        private Paginator $paginator,
    ) {
        $firstResult = $paginator->getQuery()->getFirstResult();
        $maxResults = $paginator->getQuery()->getMaxResults();

        if (null === $maxResults) {
            throw new \InvalidArgumentException('Missing maxResults from the query.');
        }

        $this->firstResult = $firstResult;
        $this->maxResults = $maxResults;
    }

    #[\Override]
    public function getItemsPerPage(): int
    {
        return $this->maxResults;
    }

    #[\Override]
    public function getCurrentPage(): int
    {
        if ($this->maxResults <= 0) {
            return 1;
        }

        return (int) (1 + floor($this->firstResult / $this->maxResults));
    }

    #[\Override]
    public function getLastPage(): int
    {
        if ($this->maxResults <= 0) {
            return 1;
        }

        return (int) (ceil($this->getTotalItems() / $this->maxResults) ?: 1);
    }

    #[\Override]
    public function getTotalItems(): int
    {
        return count($this->paginator);
    }

    #[\Override]
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    #[\Override]
    public function getIterator(): \Traversable
    {
        return $this->paginator->getIterator();
    }
}
