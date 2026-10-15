<?php

namespace Wexample\SymfonyOrganizationDs\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\TwigFunction;
use Wexample\SymfonyHelpers\Twig\AbstractExtension;
use Wexample\SymfonyOrganization\Entity\Organization;
use Wexample\SymfonyOrganizationDs\Service\OrganizationSectionRegistryService;

class OrganizationSectionExtension extends AbstractExtension
{
    public function __construct(
        private readonly OrganizationSectionRegistryService $registry,
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('organization_sections', $this->organizationSections(...)),
            new TwigFunction('organization_navigation', $this->organizationNavigation(...)),
        ];
    }

    /**
     * The sections other packages add to the pages of $organization.
     */
    public function organizationSections(Organization $organization): array
    {
        return $this->registry->getSections(
            $organization,
            $this->requestStack->getCurrentRequest()?->attributes->get('_route')
        );
    }

    /**
     * The navigation of the pages of $organization, as the design system draws
     * it (NavigationService): its overview, then what the other packages add —
     * the books and every view of them —, left for the list of organizations.
     * Every page about the organization shows it, a package's own among them.
     */
    public function organizationNavigation(Organization $organization): array
    {
        $params = ['id' => $organization->getId()];

        return [
            'label' => $organization->getName(),
            'href' => $this->urlGenerator->generate('organization_show', $params),
            'back' => [
                'href' => $this->urlGenerator->generate('organization_index'),
                'label' => $this->translator->trans('WexampleSymfonyOrganizationDsBundle.pages.organizations.index::page_title'),
            ],
            'items' => [
                [
                    'label' => 'WexampleSymfonyOrganizationDsBundle.layouts.organization.layout::tab.overview',
                    'icon' => 'ph:bold/gauge',
                    'route' => 'organization_show',
                    'params' => $params,
                ],
                ...$this->organizationSections($organization),
            ],
        ];
    }
}
