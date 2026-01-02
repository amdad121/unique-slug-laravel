<?php

declare(strict_types=1);

return [
    'update_on_update' => env('SLUG_UPDATE_ON_UPDATE', false),

    'case' => env('SLUG_CASE', 'lower'),

    'max_length' => env('SLUG_MAX_LENGTH', 255),

    'reserved_slugs' => array_filter(explode(',', env('SLUG_RESERVED_SLUGS', ''))),

    'suffix_separator' => env('SLUG_SUFFIX_SEPARATOR', '-'),

    'skip_on_empty' => env('SLUG_SKIP_ON_EMPTY', false),

    'include_soft_deleted' => env('SLUG_INCLUDE_SOFT_DELETED', false),
];
