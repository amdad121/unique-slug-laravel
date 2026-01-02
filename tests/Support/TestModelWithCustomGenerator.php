<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug\Tests\Support;

use AmdadulHaq\UniqueSlug\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TestModelWithCustomGenerator extends Model
{
    use HasSlug;

    protected $table = 'test_models';

    protected $guarded = [];

    protected function generateCustomSlug(string $source): string
    {
        return 'custom-'.Str::slug($source);
    }
}
