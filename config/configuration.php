<?php

return [
    // Featured Flag
    'featured_flag' => [
        'name' => 'featured_flag',
        'title' => 'Featured Flag1',
        'info' => 'Featured Flag11',
        'sort' => 1,
        'icon' => 'pi pi-flag',
        'is_active' => false,
        'sections' => [
            [
                'title' => 'Featured Flag2',
                'info' => 'Featured Flag22',
                'name' => null,
                'icon' => 'pi pi-palette',
                'fields' => [
                    [
                        'name' => 'featured_flag.is_theme_customize',
                        'title' => 'Theme Customize',
                        'info' => 'Enable theme customization in mobile app',
                        'type' => 'boolean',
                        'default' => true,
                    ]
                ],
            ]
        ]
    ],

    // Api Integration
    'api_integration' => [
        'name' => 'api_integration',
        'title' => 'Api Integration',
        'info' => 'Api Integration',
        'sort' => 1,
        'icon' => 'pi pi-link',
        'is_active' => false,
        'sections' => [
            [
                'title' => 'Mobile SMS',
                'info' => 'Fill SMS integration deatils',
                'name' => 'sms',
                'icon' => 'pi pi-comment',
                'fields' => [
                    [
                        'name' => 'api_integration.sms.key',
                        'title' => 'Key',
                        'info' => 'SMS key',
                        'type' => 'text',
                        'validation' => 'required',
                    ],
                    [
                        'name' => 'api_integration.sms.url',
                        'title' => 'URL',
                        'info' => 'SMS URL',
                        'type' => 'text',
                        'validation' => 'required',
                    ],
                ],
            ],

            [
                'title' => 'Whatsapp SMS',
                'info' => 'Fill whatsapp sms integration deatils',
                'name' => 'whatsapp',
                'icon' => 'pi pi-whatsapp',
                'fields' => [
                    [
                        'name' => 'api_integration.whatsapp.key',
                        'title' => 'Key',
                        'info' => 'whatsapp key',
                        'type' => 'text',
                        'validation' => 'required',
                    ],
                    [
                        'name' => 'api_integration.whatsapp.url',
                        'title' => 'URL',
                        'info' => 'whatsapp URL',
                        'type' => 'text',
                        'validation' => 'required',
                    ],
                ],
            ]
        ]
    ],

    // Website
    'website' => [
        'name' => 'website',
        'title' => 'Website',
        'info' => 'Website',
        'sort' => 1,
        'icon' => 'pi pi-globe',
        'is_active' => true,
        'sections' => [
            [
                'title' => 'Club Music',
                'info' => 'Fill Club Music deatils',
                'name' => 'music',
                'icon' => 'pi pi-volume-up',
                'fields' => [
                    // [
                    //     'name' => 'website.music.is_default_on',
                    //     'info' => 'Play music by default on website always ?',
                    //     'title' => 'Play Music',
                    //     'type' => 'boolean',
                    //     'default' => true,
                    // ],
                    [
                        'name' => 'website.music.music',
                        'info' => 'Allow mp3,wav and aac file types.',
                        'title' => 'Music',
                        'type' => 'audio',
                        'validation' => 'ext:mp3,wav,aac|max:102400',
                        'accept' => 'audio/*',
                    ]
                ],
            ]
        ]
    ],

];
