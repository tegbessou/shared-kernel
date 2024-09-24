<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Yaml\Yaml;

class TegCorpSharedKernelBundle extends AbstractBundle
{
    public function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(__DIR__.'/../config/{services,messenger}.yaml');
    }

    /*public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $configs = Yaml::parseFile(__DIR__.'/../config/messenger.yaml');
        $builder->prependExtensionConfig('framework', $configs['messenger']);
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__.'/../config'));
        $loader->load('messenger.yaml');
        $loader->load('services.yaml');
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }*/
}