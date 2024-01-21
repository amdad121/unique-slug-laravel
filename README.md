# Unique slug generator for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/amdad121/unique-slug-laravel.svg?style=flat-square)](https://packagist.org/packages/amdad121/unique-slug-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/unique-slug-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/amdad121/unique-slug-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/unique-slug-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/amdad121/unique-slug-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/amdad121/unique-slug-laravel.svg?style=flat-square)](https://packagist.org/packages/amdad121/unique-slug-laravel)

This is simple unique slug generator package for Laravel. It is easy to use your any Laravel project.

## Installation

You can install the package via composer:

```bash
composer require amdad121/unique-slug-laravel
```

## Usage

```php
namespace App\Models;

use AmdadulHaq\UniqueSlug\HasSlug;
// ...

class User extends Authenticatable
{
    use HasSlug;

    // Optionally you can configure
    public function getSlugSourceAttribute(): string
    {
        return 'name'; // Default attribute to generate slug from
    }

    public function getSlugAttribute(): string
    {
        return 'slug'; // Default attribute to store the slug
    }

    public function getSlugSeparator(): string
    {
        return '-'; // Default separator for the slug
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Amdadul Haq](https://github.com/amdad121)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
