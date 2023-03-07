<?php

return [
    'directory' => env('BITPANDA_CHANGELOG_GENERATOR_DIRECTORY', app_path('../changelog/')),
    'allowed_types' => [
        'added',
        'changed',
        'deprecated',
        'fixed',
        'removed',
    ],
    'markdown_title_tag_and_time' => '###',
    'markdown_title_type' => '####'
];
