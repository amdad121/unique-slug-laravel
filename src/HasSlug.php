<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

/** @phpstan-ignore trait.unused */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model): void {
            $model->generateSlugIfNeeded();
        });

        static::updating(function ($model): void {
            if (config('slug.update_on_update', false)) {
                $model->generateSlugIfNeeded();
            }
        });
    }

    protected function generateSlugIfNeeded(): void
    {
        if ($this->shouldSkipSlugGeneration()) {
            return;
        }

        $slugSource = $this->getSlugSourceAttribute();
        if (! $this->isDirty($slugSource)) {
            return;
        }

        $slug = $this->generateSlugFromSource($slugSource);

        if (empty($slug) && config('slug.skip_on_empty', false)) {
            return;
        }

        $slug = $this->applySlugCase($slug);
        $slug = $this->truncateSlug($slug);
        $slug = $this->ensureUniqueSlug($slug);

        $slugAttribute = $this->getSlugNameAttribute();
        $this->$slugAttribute = $slug;
    }

    protected function shouldSkipSlugGeneration(): bool
    {
        $skipOnEmpty = config('slug.skip_on_empty', false);
        $slugSource = $this->getSlugSourceAttribute();

        if ($skipOnEmpty && empty($this->$slugSource)) {
            return true;
        }

        return method_exists($this, 'shouldSkipSlug') && $this->shouldSkipSlug();
    }

    protected function generateSlugFromSource(string $slugSource): string
    {
        $source = $this->$slugSource;

        if (method_exists($this, 'generateCustomSlug')) {
            return $this->generateCustomSlug($source);
        }

        return Str::slug($source, $this->getSlugSeparator());
    }

    protected function applySlugCase(string $slug): string
    {
        $case = config('slug.case', 'lower');

        return match ($case) {
            'upper' => strtoupper($slug),
            'lower' => strtolower($slug),
            'title' => ucwords(str_replace(['-', '_'], ' ', $slug)),
            'camel' => Str::camel($slug),
            'snake' => Str::snake($slug),
            default => $slug,
        };
    }

    protected function truncateSlug(string $slug): string
    {
        $maxLength = config('slug.max_length', 255);

        return Str::limit($slug, $maxLength, '');
    }

    protected function ensureUniqueSlug(string $slug): string
    {
        $reservedSlugs = config('slug.reserved_slugs', []);
        if (in_array($slug, $reservedSlugs)) {
            $slug = $this->addSuffix($slug, 1);
        }

        $existingSlugs = $this->getExistingSlugs($slug);

        if (! in_array($slug, $existingSlugs)) {
            return $slug;
        }

        $count = 1;
        while (in_array($this->addSuffix($slug, $count), $existingSlugs)) {
            $count++;
        }

        return $this->addSuffix($slug, $count);
    }

    protected function addSuffix(string $slug, int $count): string
    {
        $separator = config('slug.suffix_separator', '-');

        return sprintf('%s%s%d', $slug, $separator, $count);
    }

    protected function getExistingSlugs(string $slug): array
    {
        $slugAttribute = $this->getSlugNameAttribute();
        $query = static::where($slugAttribute, 'LIKE', $slug.'%');

        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        if (config('slug.include_soft_deleted', false) && in_array(SoftDeletingScope::class, array_keys($this->getGlobalScopes()))) {
            $query->withTrashed();
        }

        return $query->pluck($slugAttribute)->toArray();
    }

    protected function scopeWhereSlug(Builder $query, string $slug): Builder
    {
        return $query->where($this->getSlugNameAttribute(), $slug);
    }

    protected function scopeOrWhereSlug(Builder $query, string $slug): Builder
    {
        return $query->orWhere($this->getSlugNameAttribute(), $slug);
    }

    protected function scopeWhereSlugLike(Builder $query, string $slug): Builder
    {
        return $query->where($this->getSlugNameAttribute(), 'LIKE', $slug.'%');
    }

    protected function getSlugSourceAttribute(): string
    {
        return 'name';
    }

    protected function getSlugNameAttribute(): string
    {
        return 'slug';
    }

    public function getSlugSeparator(): string
    {
        return '-';
    }
}
