<?php

declare(strict_types=1);

namespace AmdadulHaq\UniqueSlug\Tests\Support;

use AmdadulHaq\UniqueSlug\HasSlug;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasSlug;

    protected $table = 'test_models';

    protected $guarded = [];
}
