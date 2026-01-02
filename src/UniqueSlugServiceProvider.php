<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug;

use Illuminate\Support\ServiceProvider;

class UniqueSlugServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/slug.php',
            'slug'
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/slug.php' => config_path('slug.php'),
            ], 'unique-slug-config');
        }
    }
}
