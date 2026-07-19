<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    |
    | All ACLs related to dashboard will be placed here.
    |
    */
    'users' => [
        [
            [
                'key' => 'users.index',
                'name' => 'Users',
                'route' => 'admin.users.index',
                'sort' => 1,
            ],
            [
                'key' => 'users.store',
                'name' => 'Create User',
                'route' => 'admin.users.store',
                'sort' => 1,
            ],
            [
                'key' => 'users.update',
                'name' => 'Update User',
                'route' => [
                    'admin.users.update',
                    'admin.users.edit',
                ],
                'sort' => 1,
            ],
            [
                'key' => 'users.delete',
                'name' => 'Delete User',
                'route' => 'admin.users.delete',
                'sort' => 1,
            ],
        ] 
    ]
];