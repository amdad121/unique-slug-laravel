<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if ($model->isDirty($model->getSlugSourceAttribute())) {
                $slugSource = $model->getSlugSourceAttribute();
                $slug = Str::slug($model->$slugSource, $model->getSlugSeparator());

                $slugAttribute = $model->getSlugNameAttribute();

                $existingSlugs = static::where($slugAttribute, 'LIKE', $slug.'%')
                    ->where($model->getKeyName(), '!=', $model->getKey())
                    ->pluck($slugAttribute)
                    ->toArray();

                $model->$slugAttribute = self::generateUniqueSlug($slug, $existingSlugs);
            }
        });

        static::updating(function ($model) {
            // Check if slug update is enabled in the configuration
            $shouldUpdateSlug = config('slug.update_on_update', false);

            if ($shouldUpdateSlug && $model->isDirty($model->getSlugSourceAttribute())) {
                $slugSource = $model->getSlugSourceAttribute();
                $slug = Str::slug($model->$slugSource, $model->getSlugSeparator());

                $slugAttribute = $model->getSlugNameAttribute();

                // Fetch all similar slugs from the database in a single query
                $existingSlugs = static::where($slugAttribute, 'LIKE', $slug.'%')
                    ->where($model->getKeyName(), '!=', $model->getKey())
                    ->pluck($slugAttribute)
                    ->toArray();

                // Generate a unique slug using the helper method
                $model->$slugAttribute = self::generateUniqueSlug($slug, $existingSlugs);
            }
        });
    }

    protected static function generateUniqueSlug(string $baseSlug, array $existingSlugs): string
    {
        if (! in_array($baseSlug, $existingSlugs)) {
            return $baseSlug; // Slug is already unique
        }

        $count = 1;
        while (in_array("{$baseSlug}-{$count}", $existingSlugs)) {
            $count++;
        }

        return "{$baseSlug}-{$count}";
    }

    public function getSlugSourceAttribute(): string
    {
        return 'name'; // Default attribute to generate slug from
    }

    public function getSlugNameAttribute(): string
    {
        return 'slug'; // Default attribute to store the slug
    }

    public function getSlugSeparator(): string
    {
        return '-'; // Default separator for the slug
    }
}
