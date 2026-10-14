<?php

namespace Wexample\SymfonyOrganizationDs\Traits;

use Wexample\SymfonyHelpers\Traits\BundleClassTrait;
use Wexample\SymfonyOrganizationDs\WexampleSymfonyOrganizationDsBundle;

trait SymfonyOrganizationDsBundleClassTrait
{
    use BundleClassTrait;

    public static function getBundleClassName(): string
    {
        return WexampleSymfonyOrganizationDsBundle::class;
    }
}
