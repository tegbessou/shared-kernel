<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Yaml\Yaml;
use TegCorp\SharedKernelBundle\Application\Command\AsCommandHandler;
use TegCorp\SharedKernelBundle\Application\Query\AsQueryHandler;

class TegCorpSharedKernelBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container)
    {
        $container->registerAttributeForAutoconfiguration(AsQueryHandler::class, static function (ChildDefinition $definition): void {
            $definition->addTag('messenger.message_handler', ['bus' => 'query.bus']);
        });

        $container->registerAttributeForAutoconfiguration(AsCommandHandler::class, static function (ChildDefinition $definition): void {
            $definition->addTag('messenger.message_handler', ['bus' => 'command.bus']);
        });
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $config = Yaml::parseFile(__DIR__.'/../config/messenger.yaml');

        if (isset($config['framework'])) {
            $builder->prependExtensionConfig('framework', $config['framework']);
        }
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__.'/../config'));
        $loader->load('services.yaml');
    }
}
