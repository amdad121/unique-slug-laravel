# Unique slug generator for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/amdadulhaq/unique-slug-laravel.svg?style=flat-square)](https://packagist.org/packages/amdadulhaq/unique-slug-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/unique-slug-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/amdad121/unique-slug-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/unique-slug-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/amdad121/unique-slug-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/amdadulhaq/unique-slug-laravel.svg?style=flat-square)](https://packagist.org/packages/amdadulhaq/unique-slug-laravel)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-11%2F12%2F13-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Sponsor](https://img.shields.io/badge/Sponsor-%E2%9D%A4-pink?style=flat-square&logo=github)](https://github.com/sponsors/amdad121)

A powerful and flexible unique slug generator package for Laravel with advanced features.

## Requirements

- PHP 8.2, 8.3, 8.4, or 8.5
- Laravel 11, 12, or 13

## Installation

You can install the package via composer:

```bash
composer require amdadulhaq/unique-slug-laravel
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=unique-slug-config
```

## Usage

### Basic Usage

```php
namespace App\Models;

use AmdadulHaq\UniqueSlug\HasSlug;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug'];
}
```

```php
Article::create(['name' => 'Hello World']);
// Generates slug: hello-world

Article::create(['name' => 'Hello World']);
// Generates slug: hello-world-1
```

### Configuration Options

#### Custom Source and Slug Fields

```php
class Article extends Model
{
    use HasSlug;

    public function getSlugSourceAttribute(): string
    {
        return 'title'; // Generate slug from title field
    }

    public function getSlugNameAttribute(): string
    {
        return 'slug'; // Store slug in slug field
    }

    public function getSlugSeparator(): string
    {
        return '_'; // Use underscore separator
    }
}
```

#### Using Configuration File

```php
// config/slug.php
return [
    'update_on_update' => env('SLUG_UPDATE_ON_UPDATE', false),
    'case' => env('SLUG_CASE', 'lower'),
    'max_length' => env('SLUG_MAX_LENGTH', 255),
    'reserved_slugs' => ['admin', 'dashboard', 'api'],
    'suffix_separator' => env('SLUG_SUFFIX_SEPARATOR', '-'),
    'skip_on_empty' => env('SLUG_SKIP_ON_EMPTY', false),
    'include_soft_deleted' => env('SLUG_INCLUDE_SOFT_DELETED', false),
];
```

### Advanced Features

#### Custom Slug Generation

```php
class Article extends Model
{
    use HasSlug;

    protected function generateCustomSlug(string $source): string
    {
        return 'article-'.strtolower($source);
    }
}
```

#### Conditional Slug Generation

```php
class Article extends Model
{
    use HasSlug;

    public function shouldSkipSlug(): bool
    {
        return $this->published === false;
    }
}
```

#### Case Transformation

Available cases: `lower`, `upper`, `title`, `camel`, `snake`

```php
config(['slug.case' => 'upper']);
Article::create(['name' => 'Hello World']);
// Generates slug: HELLO-WORLD
```

> **Note:** `snake` has no visible effect on the default hyphen-separated output. By the time case is applied, the slug has already gone through `Str::slug()`, which lowercases and hyphenates the whole string — leaving no camelCase boundary left for `Str::snake()` to convert to underscores. If you want underscores, use a custom separator instead (see [Custom Source and Slug Fields](#custom-source-and-slug-fields)) rather than `slug.case`.

#### Maximum Length

```php
config(['slug.max_length' => 50]);
Article::create(['name' => 'Very Long Title That Should Be Truncated']);
// Truncates slug to 50 characters
```

#### Reserved Slugs

```php
config(['slug.reserved_slugs' => ['admin', 'dashboard']]);
Article::create(['name' => 'admin']);
// Generates slug: admin-1
```

### Query Scopes

```php
Article::whereSlug('hello-world')->first();
Article::orWhereSlug('another-slug')->get();
Article::whereSlugLike('hello')->get();
```

### Soft Delete Support

```php
class Article extends Model
{
    use HasSlug;
    use \Illuminate\Database\Eloquent\SoftDeletes;
}

// Include soft deleted records in uniqueness check
config(['slug.include_soft_deleted' => true]);
```

## Environment Variables

```env
SLUG_UPDATE_ON_UPDATE=false
SLUG_CASE=lower
SLUG_MAX_LENGTH=255
SLUG_RESERVED_SLUGS=admin,dashboard,api
SLUG_SUFFIX_SEPARATOR=-
SLUG_SKIP_ON_EMPTY=false
SLUG_INCLUDE_SOFT_DELETED=false
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
