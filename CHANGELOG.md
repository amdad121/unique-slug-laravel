# Changelog

All notable changes to `unique-slug-laravel` will be documented in this file.

## v3.0.0 - 2026-07-21

### Fixed
- `getExistingSlugs()` called `$query->withTrashed()`, a method added dynamically by `SoftDeletingScope` only for models that use `SoftDeletes` — replaced with `withoutGlobalScope(SoftDeletingScope::class)`, which is what `withTrashed()` does internally, but is always statically available on `Builder`.
- Replaced the optional-hook detection for `shouldSkipSlug()`/`generateCustomSlug()` with `ReflectionObject`-based dispatch instead of `method_exists()`, since these methods are only conditionally defined by consuming models. Behavior is unchanged.
- Added missing type declarations across `HasSlug` (array generics, `Builder` generics) — this trait was previously excluded from static analysis entirely (reported as "used zero times"), so these gaps had never been caught.

### Removed
- Dropped Laravel 10 support (minimum is now Laravel 11)

## v1.0.2 - 2024-10-10

**Full Changelog**: https://github.com/amdad121/unique-slug-laravel/compare/v1.0.1...v1.0.2

## v1.1.0 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/unique-slug-laravel/compare/v1.0.3...v1.1.0

## v1.0.3 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/unique-slug-laravel/compare/v1.0.2...v1.0.3

## v1.0.1 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/unique-slug-laravel/compare/v1.0.0...v1.0.1

## v1.0.0 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/unique-slug-laravel/commits/v1.0.0
