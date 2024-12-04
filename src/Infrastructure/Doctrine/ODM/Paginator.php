<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Infrastructure\Doctrine\ODM;

use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\Query\Builder;

/**
 * The paginator can handle various complex scenarios with DQL.
 *
 * @template-covariant T
 *
 * @implements \IteratorAggregate<array-key, T>
 */
final class Paginator implements \Countable, \IteratorAggregate
{
    private Builder $query;
    private ?array $results = null;
    private ?int $count = null;

    public function __construct(Builder $query)
    {
        $this->query = clone $query;
    }

    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        if (null === $this->count) {
            $countQueryBuild = clone $this->query;

            /** @var \Countable $countQuery */
            $countQuery = $countQueryBuild
                ->getQuery()
                ->execute();

            $this->count = $countQuery->count();
        }

        return $this->count;
    }

    public function getIterator(): \Traversable
    {
        if (null === $this->results) {
            $skip = $this->getQuery()->getQuery()->getQuery()['skip'] ?? 0;
            $limit = $this->getQuery()->getQuery()->getQuery()['limit'] ?? 0;

            /** @var Iterator<mixed> $results */
            $results = $this->query
                ->skip($skip)
                ->limit($limit)
                ->getQuery()
                ->execute();

            $this->results = $results->toArray();
        }

        return new \ArrayIterator($this->results);
    }
}
