<?php

return [
    /**
     * Domain to exclude from download
     * @var array
     */
    'exclude_domains' => [
        'fonts.googleapis.com',
        'maps.googleapis.com',
    ],

    /**
     * Font extensions
     */
    'font_extensions' => [
        'woff',
        'woff2',
        'ttf',
        'eot',
        'otf',
    ],

    /**
     * Default layout for download template
     */
    'default_layout' => 'layouts.app',
];
