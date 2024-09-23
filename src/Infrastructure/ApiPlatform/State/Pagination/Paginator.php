<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Infrastructure\ApiPlatform\State\Pagination;

use ApiPlatform\State\Pagination\PaginatorInterface;

/**
 * @template T of object
 *
 * @implements PaginatorInterface<T>
 * @implements \IteratorAggregate<T>
 */
final readonly class Paginator implements PaginatorInterface, \IteratorAggregate
{
    /**
     * @param \Traversable<T> $items
     */
    public function __construct(
        private \Traversable $items,
        private float $currentPage,
        private float $itemsPerPage,
        private float $lastPage,
        private float $totalItems,
    ) {
    }

    #[\Override]
    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    #[\Override]
    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
    }

    #[\Override]
    public function getLastPage(): float
    {
        return $this->lastPage;
    }

    #[\Override]
    public function getTotalItems(): float
    {
        return $this->totalItems;
    }

    #[\Override]
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    /**
     * @return \Traversable<T>
     */
    #[\Override]
    public function getIterator(): \Traversable
    {
        return $this->items;
    }
}
