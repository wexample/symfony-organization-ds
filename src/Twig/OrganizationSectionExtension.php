<?php

namespace Wexample\SymfonyOrganizationDs\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\TwigFunction;
use Wexample\SymfonyHelpers\Twig\AbstractExtension;
use Wexample\SymfonyOrganization\Entity\Organization;
use Wexample\SymfonyOrganizationDs\Service\OrganizationSectionRegistryService;

class OrganizationSectionExtension extends AbstractExtension
{
    public function __construct(
        private readonly OrganizationSectionRegistryService $registry,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('organization_sections', $this->organizationSections(...)),
        ];
    }

    /**
     * The tabs other packages add to the pages of $organization.
     */
    public function organizationSections(Organization $organization): array
    {
        return $this->registry->getSections(
            $organization,
            $this->requestStack->getCurrentRequest()?->attributes->get('_route')
        );
    }
}
