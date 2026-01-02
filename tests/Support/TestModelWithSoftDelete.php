<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug\Tests\Support;

use AmdadulHaq\UniqueSlug\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestModelWithSoftDelete extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $table = 'test_models';

    protected $guarded = [];
}
