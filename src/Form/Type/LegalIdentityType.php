<?php

namespace Wexample\SymfonyOrganizationDs\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wexample\SymfonyForms\Form\Type\EmailInputType;
use Wexample\SymfonyForms\Form\Type\SwitchInputType;
use Wexample\SymfonyForms\Form\Type\TextInputType;
use Wexample\SymfonyForms\Form\Type\UrlInputType;
use Wexample\SymfonyGeo\Entity\Address;
use Wexample\SymfonyGeo\Helper\PostalAddressHelper;
use Wexample\SymfonyGeo\Repository\AddressRepository;
use Wexample\SymfonyGeoDs\Form\Type\PostalAddressType;
use Wexample\SymfonyOrganization\Entity\Organization;

/**
 * The legal identity of an organization of the directory, as one field of
 * any form: its name, its identifiers, its contacts, and the address it
 * points at (a row of symfony-geo's address table, edited through the postal
 * address of symfony-geo-ds). A form editing a ledger or a party adds it on
 * their `organization`: the identity typed there is the organization's, for
 * every ledger it appears in.
 *
 * Its words are its own (`common/identity`), whichever form it stands in.
 * Options show what not every identity needs: the legal form and the website
 * (the books' own company), whether it is a private person (a party) — and
 * hide the country where changing it would change more than an address (the
 * jurisdiction of a ledger).
 *
 * The country picked with the address is also the country the identity
 * answers to, the first time one is given: the registration follows the
 * address it was first known at, and nothing afterwards.
 */
class LegalIdentityType extends AbstractType
{
    private const string DOMAIN = 'WexampleSymfonyOrganizationDsBundle.common.identity';

    public function __construct(
        private readonly AddressRepository $addresses,
    ) {
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $field = static fn (bool $required = false) => ['translation_domain' => self::DOMAIN, 'required' => $required];

        $builder->add('name', TextInputType::class, ['label' => 'name'] + $field(true));

        if ($options['with_individual']) {
            $builder->add('individual', SwitchInputType::class, ['label' => 'individual'] + $field());
        }

        if ($options['with_legal_form']) {
            $builder->add('legalForm', TextInputType::class, ['label' => 'legal_form'] + $field());
        }

        $builder
            ->add('legalIdentifier', TextInputType::class, ['label' => 'legal_identifier'] + $field())
            ->add('vatNumber', TextInputType::class, ['label' => 'vat_number'] + $field());

        if ($options['with_contact']) {
            $builder
                ->add('email', EmailInputType::class, ['label' => 'email'] + $field())
                ->add('phone', TextInputType::class, ['label' => 'phone'] + $field());
        }

        if ($options['with_website']) {
            $builder->add('website', UrlInputType::class, ['label' => 'website'] + $field());
        }

        // The address is a row of its own the organization points at; typing
        // the first one for an organization that has none builds it.
        $builder->add('address', PostalAddressType::class, [
            'required' => false,
            'data_class' => Address::class,
            'empty_data' => fn (): Address => $this->addresses->createNewAddress(),
        ]);

        if (! $options['with_country']) {
            $builder->get('address')->remove('country');
        }

        $builder->addEventListener(FormEvents::SUBMIT, static function (FormEvent $event): void {
            $organization = $event->getData();
            $address = $organization->getAddress();

            if (! $address) {
                return;
            }

            // Nothing typed: no empty address row left pointed at.
            if ([] === PostalAddressHelper::toLines($address, withCountry: false)) {
                $organization->setAddress(null);

                return;
            }

            // The country it answers to is the one of the first address it is
            // given; moving afterwards changes the address, not where it is
            // registered.
            if (! $organization->getCountry()) {
                $organization->setCountry($address->getCountry());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organization::class,
            'translation_domain' => self::DOMAIN,
            'label' => false,
            'with_individual' => false,
            'with_legal_form' => false,
            'with_website' => false,
            'with_contact' => true,
            'with_country' => true,
        ]);

        foreach (['with_individual', 'with_legal_form', 'with_website', 'with_contact', 'with_country'] as $option) {
            $resolver->setAllowedTypes($option, 'bool');
        }
    }

    public function getBlockPrefix(): string
    {
        return 'legal_identity';
    }
}
