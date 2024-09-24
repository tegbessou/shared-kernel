<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class TegCorpSharedKernelExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        // Vérifier si le FrameworkBundle est disponible
        if (!$container->hasExtension('framework')) {
            die('hugues');
            throw new \LogicException('Le FrameworkBundle doit être installé et activé pour utiliser le SharedKernelBundle.');
        }

        // Charger la configuration Messenger
        $configs = Yaml::parseFile(__DIR__.'/../Symfony/Resources/config/packages/messenger.yaml');
        $container->prependExtensionConfig('framework', $configs['framework']);
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        // Charger les services du bundle
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Symfony/Resources/config/packages'));
        $loader->load('messenger.yaml');
    }
}