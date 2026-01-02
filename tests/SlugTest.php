<?php

declare(strict_types=1);

use AmdadulHaq\UniqueSlug\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

beforeEach(function (): void {
    Schema::dropIfExists('test_models');
    Schema::create('test_models', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->string('slug')->nullable();
        $table->timestamps();
        $table->softDeletes();
        $table->unique(['slug', 'deleted_at']);
    });
});

afterEach(function (): void {
    Schema::dropIfExists('test_models');
});

it('generates unique slug on creation', function (): void {
    $model = TestModel::create(['name' => 'Hello World']);
    expect($model->slug)->toBe('hello-world');
});

it('appends suffix for duplicate slugs', function (): void {
    TestModel::create(['name' => 'Hello World']);
    $model2 = TestModel::create(['name' => 'Hello World']);
    $model3 = TestModel::create(['name' => 'Hello World']);

    expect($model2->slug)->toBe('hello-world-1');
    expect($model3->slug)->toBe('hello-world-2');
});

it('does not update slug by default on update', function (): void {
    $model = TestModel::create(['name' => 'Hello World']);
    $model->update(['name' => 'Goodbye World']);

    expect($model->slug)->toBe('hello-world');
});

it('updates slug when config is enabled', function (): void {
    config(['slug.update_on_update' => true]);
    $model = TestModel::create(['name' => 'Hello World']);
    $model->update(['name' => 'Goodbye World']);

    expect($model->fresh()->slug)->toBe('goodbye-world');
});

it('applies custom separator', function (): void {
    $model = TestModelWithCustomSeparator::create(['name' => 'Hello World']);
    expect($model->slug)->toBe('hello_world');
});

it('applies case transformation', function (): void {
    config(['slug.case' => 'upper']);
    $model = TestModel::create(['name' => 'Hello World']);
    expect($model->slug)->toBe('HELLO-WORLD');
});

it('truncates slug to max length', function (): void {
    config(['slug.max_length' => 10]);
    $model = TestModel::create(['name' => 'Very Long Name That Should Be Truncated']);
    expect(strlen((string) $model->slug))->toBeLessThanOrEqual(10);
});

it('respects reserved slugs', function (): void {
    config(['slug.reserved_slugs' => ['admin', 'dashboard']]);
    $model = TestModel::create(['name' => 'admin']);
    expect($model->slug)->toBe('admin-1');
});

it('skips slug generation when source is empty and skip_on_empty is true', function (): void {
    config(['slug.skip_on_empty' => true]);
    $model = TestModel::create(['name' => '']);
    expect($model->slug)->toBeNull();
});

it('uses custom slug generator if available', function (): void {
    $model = TestModelWithCustomGenerator::create(['name' => 'Hello World']);
    expect($model->slug)->toBe('custom-hello-world');
});

it('respects shouldSkipSlug method', function (): void {
    $model = TestModelWithSkip::create(['name' => 'Hello World']);
    expect($model->slug)->toBeNull();
});

it('includes soft deleted records when configured', function (): void {
    config(['slug.include_soft_deleted' => true]);
    $model = TestModelWithSoftDelete::create(['name' => 'Hello World']);
    $model->delete();

    $model2 = TestModelWithSoftDelete::create(['name' => 'Hello World']);
    expect($model2->slug)->toBe('hello-world-1');
});

it('excludes soft deleted records by default', function (): void {
    $model = TestModelWithSoftDelete::create(['name' => 'Hello World']);
    $model->delete();

    $model2 = TestModelWithSoftDelete::create(['name' => 'Hello World']);
    expect($model2->slug)->toBe('hello-world');
});

it('provides whereSlug scope', function (): void {
    TestModel::create(['name' => 'Hello World']);
    $found = TestModel::whereSlug('hello-world')->first();

    expect($found)->not->toBeNull();
    expect($found->slug)->toBe('hello-world');
});

it('provides orWhereSlug scope', function (): void {
    TestModel::create(['name' => 'Hello World']);
    TestModel::create(['name' => 'Goodbye World']);

    $found = TestModel::whereSlug('hello-world')
        ->orWhereSlug('goodbye-world')
        ->get();

    expect($found->count())->toBe(2);
});

it('provides whereSlugLike scope', function (): void {
    TestModel::create(['name' => 'Hello World']);
    TestModel::create(['name' => 'Hello There']);

    $found = TestModel::whereSlugLike('hello')->get();

    expect($found->count())->toBe(2);
});

class TestModel extends Model
{
    use HasSlug;

    protected $table = 'test_models';

    protected $guarded = [];
}

class TestModelWithCustomSeparator extends Model
{
    use HasSlug;

    protected $table = 'test_models';

    protected $guarded = [];

    public function getSlugSeparator(): string
    {
        return '_';
    }
}

class TestModelWithSoftDelete extends Model
{
    use HasSlug;
    use SoftDeletes;

    protected $table = 'test_models';

    protected $guarded = [];
}

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
