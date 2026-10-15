<?php

namespace Wexample\SymfonyOrganizationDs\Interface;

use Wexample\SymfonyOrganization\Entity\Organization;

/**
 * What a package adds to the menu of an organization's pages: its own pages
 * about that organization — the books of accounting-ds, its bank —, or none
 * when it holds nothing for it. Implementing the interface is enough to be
 * registered (see the bundle's extension).
 */
interface OrganizationSectionInterface
{
    final public const string TAG = 'wexample_symfony_organization_ds.section';

    /**
     * Items of a navigation (the design system's NavigationService): `label`
     * (a translation key), `icon`, `route`, `params`, `match` for an item lit
     * on a family of pages, and `children` for a section holding views of its
     * own — drawn folded under it in a menu, as the next level in tabs.
     *
     * @return list<array{label: string, icon: string, route: string, params?: array<string, mixed>, match?: string|list<string>, children?: list<array<string, mixed>>}>
     */
    public function getSections(
        Organization $organization,
        ?string $currentRoute
    ): array;
}
