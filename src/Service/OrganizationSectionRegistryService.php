<?php

namespace Wexample\SymfonyOrganizationDs\Service;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Wexample\SymfonyOrganization\Entity\Organization;
use Wexample\SymfonyOrganizationDs\Interface\OrganizationSectionInterface;

/**
 * The tabs the packages add to an organization's pages, in the order their
 * bundles are registered.
 */
class OrganizationSectionRegistryService
{
    /**
     * @param iterable<OrganizationSectionInterface> $sections
     */
    public function __construct(
        #[AutowireIterator(OrganizationSectionInterface::TAG)]
        private readonly iterable $sections = [],
    ) {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getSections(
        Organization $organization,
        ?string $currentRoute = null
    ): array {
        $tabs = [];

        foreach ($this->sections as $section) {
            array_push($tabs, ...$section->getSections($organization, $currentRoute));
        }

        return $tabs;
    }
}
