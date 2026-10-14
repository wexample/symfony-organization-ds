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
