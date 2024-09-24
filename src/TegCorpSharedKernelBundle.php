<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class TegCorpSharedKernelBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // load an XML, PHP or YAML file
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__.'../config'));
        $loader->load('messenger.yaml');
        $loader->load('services.yaml');
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}