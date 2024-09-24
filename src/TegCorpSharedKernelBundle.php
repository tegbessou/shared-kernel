<?php

declare(strict_types=1);

namespace TegCorp\SharedKernelBundle;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TegCorp\SharedKernelBundle\Application\Command\AsCommandHandler;
use TegCorp\SharedKernelBundle\Application\Query\AsQueryHandler;

class TegCorpSharedKernelBundle extends Bundle
{
    #[\Override]
    public function build(ContainerBuilder $container): void
    {
        $container->registerAttributeForAutoconfiguration(AsQueryHandler::class, static function (ChildDefinition $definition): void {
            $definition->addTag('messenger.message_handler', ['bus' => 'query.bus']);
        });

        $container->registerAttributeForAutoconfiguration(AsCommandHandler::class, static function (ChildDefinition $definition): void {
            $definition->addTag('messenger.message_handler', ['bus' => 'command.bus']);
        });
    }
}