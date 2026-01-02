<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug\Tests\Support;

use AmdadulHaq\UniqueSlug\HasSlug;
use Illuminate\Database\Eloquent\Model;

class TestModelWithSkip extends Model
{
    use HasSlug;

    protected $table = 'test_models';

    protected $guarded = [];

    public function shouldSkipSlug(): bool
    {
        return true;
    }
}
