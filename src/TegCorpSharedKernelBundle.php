<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TegCorpSharedKernelBundle extends Bundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('messenger.yaml');
    }
}