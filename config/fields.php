<?php

return [
    'fields' => [
        // Select
        [
            'name' => 'weight_unit',
            'title' => 'weight_unit',
            'type' => 'select',
            'default' => 'kgs',
            'options' => [
                [
                    'title' => 'lbs',
                    'value' => 'lbs',
                ],
                [
                    'title' => 'kgs',
                    'value' => 'kgs',
                ],
            ],
        ],

        // Multi Select
        [
            'name' => 'providers',
            'title' => 'providers',
            'type' => 'multiselect',
            'options' => [],
        ],

        // Boolean
        [
            'name' => 'shop',
            'title' => 'shop',
            'type' => 'boolean',
            'default' => true,
        ],
        // Text
        [
            'name' => 'title',
            'title' => 'title',
            'type' => 'text',
            'default' => 'Get UPTO 40% OFF on your 1st order',
            'validation' => 'max:100',
        ],
        // Textarea
        [
            'name' => 'prerender_ignore_urls',
            'title' => 'prerender_ignore_urls',
            'info' => 'prerender_ignore_urls',
            'type' => 'textarea',
            'default' => 'custom_url',
            'depends' => 'title:url',
        ],
        // File: Image
        [
            'name' => 'logo_image',
            'title' => 'logo_image',
            'type' => 'image',
            'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg',
        ],
        // Password
        [
            'name' => 'api_key',
            'title' => 'api_key',
            'type' => 'password',
        ],
        // Time
        [
            'name' => 'time',
            'title' => 'time',
            'type' => 'text',
            'default' => '00:00',
            'depends' => 'enabled:true',
            'validation' => 'date_format:H:i',
        ],
        // Audio
        [
            'name' => 'Audio',
            'title' => 'Audio',
            'type' => 'audio',
        ],
    ],
];

// All possible feidls keys
[
    'name' => 'prerender_ignore_urls',
    'title' => 'prerender_ignore_urls',
    'info' => 'prerender_ignore_urls',
    'type' => 'textarea',
    'default' => 'custom_url',
    'depends' => 'title:url',
    'validation' => 'max:100',
];
