<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TegCorp\SharedKernelBundle\Infrastructure\DependencyInjection\TegCorpSharedKernelExtension;

class TegCorpSharedKernelBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new TegCorpSharedKernelExtension();
        }

        return $this->extension;
    }
}