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
     * The tabs, as `page_tabs` takes them: `label` (a translation key),
     * `icon`, `route`, `params`, and `active` for a tab lit on a family of
     * pages.
     *
     * @return list<array{label: string, icon: string, route: string, params?: array<string, mixed>, active?: bool}>
     */
    public function getSections(
        Organization $organization,
        ?string $currentRoute
    ): array;
}
