<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->isDirty($model->getSlugSourceAttribute())) {
                $slugSource = $model->getSlugSourceAttribute();
                $slug = Str::slug($model->$slugSource, $model->getSlugSeparator());

                $slugAttribute = $model->getSlugNameAttribute();
                $count = static::whereRaw("$slugAttribute RLIKE '^$slug(-[0-9]+)?$'")
                    ->where($slugSource, '!=', $model->$slugSource)
                    ->count();

                $model->$slugAttribute = $count ? "$slug-$count" : $slug;
            }
        });
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
