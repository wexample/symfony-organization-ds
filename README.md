# symfony-organization-ds

Version: 3.0.3

## LegalIdentityType

One field of any form, on an `Organization`: its name, its identifiers and VAT number, its contacts, its postal address (the `PostalAddressType` of `symfony-geo-ds`, written on the organization itself). A form editing an entity pointing to an organization adds it on that relation:

```php
$builder->add('organization', LegalIdentityType::class, ['with_individual' => true]);
```

and its template renders it with `form_widget(form.organization)`. Options:

- `with_individual` — the "a person, not a company" switch (a party);
- `with_legal_form`, `with_website` — what the books' own company states;
- `with_contact` (default true) — email and phone;
- `with_country` (default true) — off where changing the country would change more than an address (the jurisdiction of a ledger).

Its words are its own (`assets/common/identity.trans.yml`, domain `WexampleSymfonyOrganizationDsBundle.common.identity`), whichever form it stands in. The organization is validated with its entity when the relation carries `#[Assert\Valid]`: an identifier its country rejects is reported on its field.

Used by `symfony-accounting-ds` (`PartyForm`, `LedgerSettingsForm`).

## Organization pages

`/organization/` lists the organizations the signed-in account is a member of (symfony-user's `MembershipService::getGroups`); `/organization/{id}` opens one, `404` for anyone not a member. Their layout, `@WexampleSymfonyOrganizationDsBundle/layouts/organization/layout.html.twig`, gives the page `organization` and draws its tabs: the overview, then what the other packages add.

A package adds tabs by implementing `OrganizationSectionInterface` — autoconfigured, nothing to declare:

```php
public function getSections(Organization $organization, ?string $currentRoute): array
{
    return [['label' => 'MyBundle.common::tab.thing', 'icon' => 'ph:bold/cube', 'route' => 'thing_index', 'params' => ['organization' => (string) $organization->getId()]]];
}
```

An empty list when it holds nothing for that organization, or the reader may not open it. Twig reads them with `organization_sections(organization)`. `symfony-accounting-ds` adds the books and the bank of the organization's ledger.

## Table of Contents

- [LegalIdentityType](#legalidentitytype)
- [Organization pages](#organization-pages)
- [Integration in the Suite](#integration-in-the-suite)
- [Dependencies](#dependencies)
- [Versioning & Compatibility Policy](#versioning--compatibility-policy)
- [License](#license)
- [About us](#about-us)
- [Migration Notes](#migration-notes)

## Integration in the Suite

This package is part of the Wexample Suite — a collection of high-quality, modular tools designed to work seamlessly together across multiple languages and environments.

### Related Packages

The suite includes packages for configuration management, file handling, prompts, and more. Each package can be used independently or as part of the integrated suite.

## Dependencies

- php: >=8.5
- wexample/symfony-organization: >=3.0.0
- wexample/symfony-geo: >=8.0.0
- wexample/symfony-geo-ds: >=3.0.0
- wexample/symfony-forms: >=14.0.0
- wexample/symfony-helpers: >=18.0.0
- wexample/symfony-loader: >=24.0.0
- wexample/symfony-user: >=14.0.0
- wexample/symfony-design-system: >=39.0.0

## Versioning & Compatibility Policy

Wexample packages follow **Semantic Versioning** (SemVer):

- **MAJOR**: Breaking changes
- **MINOR**: New features, backward compatible
- **PATCH**: Bug fixes, backward compatible

We maintain backward compatibility within major versions and provide clear migration guides for breaking changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Free to use in both personal and commercial projects.

## About us

[Wexample](https://wexample.com) stands as a cornerstone of the digital ecosystem — a collective of seasoned engineers, researchers, and creators driven by a relentless pursuit of technological excellence. More than a media platform, it has grown into a vibrant community where innovation meets craftsmanship, and where every line of code reflects a commitment to clarity, durability, and shared intelligence.

This packages suite embodies this spirit. Trusted by professionals and enthusiasts alike, it delivers a consistent, high-quality foundation for modern development — open, elegant, and battle-tested. Its reputation is built on years of collaboration, refinement, and rigorous attention to detail, making it a natural choice for those who demand both robustness and beauty in their tools.

Wexample cultivates a culture of mastery. Each package, each contribution carries the mark of a community that values precision, ethics, and innovation — a community proud to shape the future of digital craftsmanship.

Built and maintained by [Romain Weeger](https://weeger.io).

## Migration Notes

When upgrading between major versions, refer to the migration guides in the documentation.

Breaking changes are clearly documented with upgrade paths and examples.
