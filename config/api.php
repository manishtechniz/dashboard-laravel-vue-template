<?php

return [
    'call' => env('BACKEND_URL'),

    'sanctum-auth' => env('BACKEND_URL') . '/sanctum/csrf-cookie',
    'test-local' => 'http://app.manishtechniz.local/test',
    'test' => env('BACKEND_URL') . '/api/test',
    'test-auth' => env('BACKEND_URL') . '/api/test-auth',

    'login' => [
        'verify' => env('BACKEND_URL') . '/api/auth/login',
    ],

    'users' => [
        'list' => env('BACKEND_URL') . '/api/users',
        'edit' => env('BACKEND_URL') . '/api/users/edit/:id',
        'update' => env('BACKEND_URL') . '/api/users/update/:id',
    ],

    'datagrid' => [
        'saved_filters' => [
            'index' => env('BACKEND_URL') . '/api/datagrid/saved-filters',
            'store' => env('BACKEND_URL') . '/api/datagrid/saved-filters',
            'update' => env('BACKEND_URL') . '/api/datagrid/saved-filters/:id',
            'destroy' => env('BACKEND_URL') . '/api/datagrid/saved-filters/:id',
        ],
    ],
];
