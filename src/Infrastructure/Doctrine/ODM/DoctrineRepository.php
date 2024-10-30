<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Infrastructure\Doctrine\ODM;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder;
use TegCorp\SharedKernelBundle\Domain\Repository\PaginatorInterface;
use TegCorp\SharedKernelBundle\Domain\Repository\RepositoryInterface;
use TegCorp\SharedKernelBundle\Infrastructure\Webmozart\Assert;

/**
 * @template T of object
 *
 * @implements RepositoryInterface<T>
 */
abstract class DoctrineRepository implements RepositoryInterface
{
    private ?int $page = null;
    private ?int $itemsPerPage = null;

    private Builder $queryBuilder;

    public function __construct(
        protected DocumentManager $documentManager,
        string $entityClass,
    ) {
        $this->queryBuilder = $this->documentManager->createQueryBuilder($entityClass);
    }

    #[\Override]
    public function getIterator(): \Iterator
    {
        if (null !== $paginator = $this->paginator()) {
            yield from $paginator;

            return;
        }

        /** @var \Iterator<T> $results */
        $results = $this->queryBuilder->getQuery()->execute();

        yield from $results;
    }

    #[\Override]
    public function count(): int
    {
        $paginator = $this->paginator() ?? new Paginator(clone $this->queryBuilder);

        return count($paginator);
    }

    #[\Override]
    public function paginator(): ?PaginatorInterface
    {
        if (null === $this->page || null === $this->itemsPerPage) {
            return null;
        }

        $firstResult = ($this->page - 1) * $this->itemsPerPage;
        $maxResults = $this->itemsPerPage;

        $repository = $this->filter(static function (Builder $qb) use ($firstResult, $maxResults): void {
            $qb->skip($firstResult)->limit($maxResults);
        });

        /** @var Paginator<T> $paginator */
        $paginator = new Paginator($repository->queryBuilder);

        return new DoctrinePaginator($paginator);
    }

    #[\Override]
    public function withoutPagination(): static
    {
        $cloned = clone $this;
        $cloned->page = null;
        $cloned->itemsPerPage = null;

        return $cloned;
    }

    #[\Override]
    public function withPagination(int $page, int $itemsPerPage): static
    {
        Assert::positiveInteger($page);
        Assert::positiveInteger($itemsPerPage);

        $cloned = clone $this;
        $cloned->page = $page;
        $cloned->itemsPerPage = $itemsPerPage;

        return $cloned;
    }

    /**
     * @return static<T>
     */
    protected function filter(callable $filter): static
    {
        $cloned = clone $this;
        $filter($cloned->queryBuilder);

        return $cloned;
    }

    protected function query(): Builder
    {
        return clone $this->queryBuilder;
    }

    protected function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }
}
