<?php

namespace Wexample\SymfonyOrganizationDs;

use Wexample\SymfonyHelpers\Class\AbstractBundle;
use Wexample\SymfonyHelpers\Helper\BundleHelper;
use Wexample\SymfonyHelpers\Interface\LoaderBundleInterface;

/**
 * What the screens of other packages embed to show and edit an organization
 * of the directory: one legal identity form, wherever an identity is typed.
 */
class WexampleSymfonyOrganizationDsBundle extends AbstractBundle implements LoaderBundleInterface
{
    public static function getLoaderFrontPaths(): array
    {
        return [
            BundleHelper::getBundleCssAlias(static::class) => __DIR__.'/../assets/',
        ];
    }
}
