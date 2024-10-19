<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class UniqueSlugServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('unique-slug-laravel')
            ->hasConfigFile('slug');
    }
}
