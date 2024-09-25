<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Tests;

use PHPUnit\Framework\TestCase;
use TegCorp\SharedKernelBundle\Infrastructure\Webmozart\Assert;

final class AssertTest extends TestCase
{
    public function testYearFailedToHigh(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Assert::year(2101);
    }

    public function testYearFailedToLow(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Assert::year(1899);
    }

    public function testFloatFailedToLow(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Assert::positiveFloat(-12.00);
    }
}
