<?php

namespace Wexample\SymfonyOrganizationDs\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wexample\SymfonyHelpers\DependencyInjection\AbstractWexampleSymfonyExtension;
use Wexample\SymfonyOrganizationDs\Interface\OrganizationSectionInterface;

class WexampleSymfonyOrganizationDsExtension extends AbstractWexampleSymfonyExtension
{
    public function load(
        array $configs,
        ContainerBuilder $container
    ): void {
        // A package adds tabs to an organization's pages by implementing the
        // interface, and nothing more.
        $container
            ->registerForAutoconfiguration(OrganizationSectionInterface::class)
            ->addTag(OrganizationSectionInterface::TAG);

        $this->loadConfig(
            __DIR__,
            $container
        );
    }
}
