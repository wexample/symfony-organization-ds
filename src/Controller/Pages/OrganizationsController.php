<?php

namespace Wexample\SymfonyOrganizationDs\Controller\Pages;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Wexample\SymfonyHelpers\Helper\VariableHelper;
use Wexample\SymfonyLoader\Controller\AbstractPagesController;
use Wexample\SymfonyOrganization\Entity\Organization;
use Wexample\SymfonyOrganization\Repository\OrganizationRepository;
use Wexample\SymfonyOrganizationDs\Traits\SymfonyOrganizationDsBundleClassTrait;
use Wexample\SymfonyUser\Interface\MemberInterface;
use Wexample\SymfonyUser\Service\MembershipService;

/**
 * The organizations the reader belongs to, and one of them. An organization
 * they are not a member of does not exist for them: 404.
 */
#[Route(path: '/organization/', name: 'organization_')]
final class OrganizationsController extends AbstractPagesController
{
    use SymfonyOrganizationDsBundleClassTrait;

    final public const string ROUTE_INDEX = VariableHelper::INDEX;

    final public const string ROUTE_SHOW = 'show';

    #[Route(path: '', name: self::ROUTE_INDEX)]
    public function index(MembershipService $memberships): Response
    {
        $user = $this->getUser();
        $organizations = $user instanceof MemberInterface
            ? array_values(array_filter(
                $memberships->getGroups($user),
                static fn (object $group) => $group instanceof Organization
            ))
            : [];

        usort($organizations, static fn (Organization $a, Organization $b) => strnatcasecmp($a->getName(), $b->getName()));

        return $this->renderPage(self::ROUTE_INDEX, [
            'organizations' => $organizations,
        ]);
    }

    #[Route(path: '{id}', name: self::ROUTE_SHOW, requirements: ['id' => Requirement::UUID])]
    public function show(
        string $id,
        OrganizationRepository $organizations,
        MembershipService $memberships,
    ): Response {
        return $this->renderPage(self::ROUTE_SHOW, [
            'organization' => $this->reachable($organizations->find($id), $memberships),
        ]);
    }

    private function reachable(
        ?Organization $organization,
        MembershipService $memberships
    ): Organization {
        $user = $this->getUser();

        if (! $organization || ! $user instanceof MemberInterface || ! $memberships->isMember($user, $organization)) {
            throw new NotFoundHttpException();
        }

        return $organization;
    }
}
