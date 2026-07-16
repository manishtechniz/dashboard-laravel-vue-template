<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Datagrid Route Prefix
    |--------------------------------------------------------------------------
    |
    | This is the URI prefix for all datagrid routes.
    |
    */
    'route_prefix' => 'datagrid',

    /*
    |--------------------------------------------------------------------------
    | Default Middlewares
    |--------------------------------------------------------------------------
    |
    | The default middleware applied to your package routes.
    |
    */
    'middlewares' => ['web', 'identifyRoute'], 
];