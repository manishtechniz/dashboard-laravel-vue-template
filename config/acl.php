<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Access Control List (ACL)
    |--------------------------------------------------------------------------
    |
    | All ACLs related to the admin panel are defined here.
    |
    */

    'dashboard' => [
        [
            [
                'key' => 'dashboard',
                'name' => 'Dashboard',
                'description' => 'View the main admin dashboard.',
                'route' => 'admin.dashboard',
                'sort' => 1,
            ],
        ]
    ],

    'profile' => [
        [
            [
                'key' => 'profile.index',
                'name' => 'View Profile',
                'description' => 'View admin profile details.',
                'route' => 'admin.profile.index',
                'sort' => 1,
            ],
            [
                'key' => 'profile.update',
                'name' => 'Update Profile',
                'description' => 'Update admin profile information.',
                'route' => 'admin.profile.update',
                'sort' => 2,
            ],
        ]
    ],

    'roles' => [
        [
            [
                'key' => 'roles.index',
                'name' => 'Roles',
                'description' => 'View list of admin roles.',
                'route' => 'admin.roles.index',
                'sort' => 1,
            ],
            [
                'key' => 'roles.store',
                'name' => 'Create Role',
                'description' => 'Create new admin roles.',
                'route' => 'admin.roles.store',
                'sort' => 2,
            ],
            [
                'key' => 'roles.update',
                'name' => 'Update Role',
                'description' => 'Update existing admin roles.',
                'route' => 'admin.roles.update',
                'sort' => 3,
            ],
            [
                'key' => 'roles.delete',
                'name' => 'Delete Role',
                'description' => 'Delete admin roles.',
                'route' => 'admin.roles.delete',
                'sort' => 4,
            ],
        ]
    ],

    'users' => [
        [
            [
                'key' => 'users.index',
                'name' => 'Users',
                'description' => 'View list of system users.',
                'route' => 'admin.users.index',
                'sort' => 1,
            ],
            [
                'key' => 'users.store',
                'name' => 'Create User',
                'description' => 'Create new system users.',
                'route' => 'admin.users.store',
                'sort' => 2,
            ],
            [
                'key' => 'users.update',
                'name' => 'Update User',
                'description' => 'Update existing system users.',
                'route' => [
                    'admin.users.update',
                    'admin.users.edit',
                ],
                'sort' => 3,
            ],
            [
                'key' => 'users.delete',
                'name' => 'Delete User',
                'description' => 'Delete system users.',
                'route' => 'admin.users.delete',
                'sort' => 4,
            ],
        ]
    ],

    'clients' => [
        [
            [
                'key' => 'clients.index',
                'name' => 'Clients',
                'description' => 'View list of clients.',
                'route' => 'admin.clients.index',
                'sort' => 1,
            ],
            [
                'key' => 'clients.store',
                'name' => 'Create Client',
                'description' => 'Create new clients.',
                'route' => 'admin.clients.store',
                'sort' => 2,
            ],
            [
                'key' => 'clients.update',
                'name' => 'Update Client',
                'description' => 'Update existing clients.',
                'route' => [
                    'admin.clients.update',
                    'admin.clients.edit',
                ],
                'sort' => 3,
            ],
            [
                'key' => 'clients.delete',
                'name' => 'Delete Client',
                'description' => 'Delete clients.',
                'route' => 'admin.clients.delete',
                'sort' => 4,
            ],
        ]
    ],

    'clubs' => [
        [
            // Club Management
            [
                'key' => 'clubs.index',
                'name' => 'Clubs',
                'description' => 'View list of clubs and branches.',
                'route' => 'admin.clubs.index',
                'sort' => 1,
            ],
            [
                'key' => 'clubs.store',
                'name' => 'Create Club',
                'description' => 'Create new clubs.',
                'route' => 'admin.clubs.store_club',
                'sort' => 2,
            ],
            [
                'key' => 'clubs.update',
                'name' => 'Update Club',
                'description' => 'Update existing clubs.',
                'route' => [
                    'admin.clubs.update_club',
                    'admin.clubs.edit',
                ],
                'sort' => 3,
            ],
            [
                'key' => 'clubs.delete',
                'name' => 'Delete Club',
                'description' => 'Delete clubs.',
                'route' => 'admin.clubs.delete_club',
                'sort' => 4,
            ], 
        ]
    ],

    // 'floors' => [
    //     [
    //         [
    //             'key' => 'floors.index',
    //             'name' => 'Floors',
    //             'description' => 'View list of floors.',
    //             'route' => 'admin.floors.index',
    //             'sort' => 1,
    //         ],
    //         [
    //             'key' => 'floors.store',
    //             'name' => 'Create Floor',
    //             'description' => 'Create new floors.',
    //             'route' => 'admin.floors.store',
    //             'sort' => 2,
    //         ],
    //         [
    //             'key' => 'floors.update',
    //             'name' => 'Update Floor',
    //             'description' => 'Update existing floors.',
    //             'route' => [
    //                 'admin.floors.update',
    //                 'admin.floors.edit',
    //             ],
    //             'sort' => 3,
    //         ],
    //         [
    //             'key' => 'floors.delete',
    //             'name' => 'Delete Floor',
    //             'description' => 'Delete floors.',
    //             'route' => 'admin.floors.delete',
    //             'sort' => 4,
    //         ],
    //     ]
    // ],

    'tables' => [
        [
            [
                'key' => 'tables.index',
                'name' => 'Tables',
                'description' => 'View list of tables.',
                'route' => 'admin.tables.index',
                'sort' => 1,
            ],
            [
                'key' => 'tables.store',
                'name' => 'Create Table',
                'description' => 'Create new tables.',
                'route' => 'admin.tables.store',
                'sort' => 2,
            ],
            [
                'key' => 'tables.update',
                'name' => 'Update Table',
                'description' => 'Update existing tables.',
                'route' => [
                    'admin.tables.update',
                    'admin.tables.edit',
                ],
                'sort' => 3,
            ],
            [
                'key' => 'tables.delete',
                'name' => 'Delete Table',
                'description' => 'Delete tables.',
                'route' => 'admin.tables.delete',
                'sort' => 4,
            ],
        ]
    ],

    'events' => [
        [
            [
                'key' => 'events.index',
                'name' => 'Events',
                'description' => 'View list of events.',
                'route' => 'admin.events.index',
                'sort' => 1,
            ],
            [
                'key' => 'events.store',
                'name' => 'Create Event',
                'description' => 'Create new events.',
                'route' => 'admin.events.store',
                'sort' => 2,
            ],
            [
                'key' => 'events.update',
                'name' => 'Update Event',
                'description' => 'Update existing events.',
                'route' => [
                    'admin.events.update',
                    'admin.events.edit',
                ],
                'sort' => 3,
            ],
            [
                'key' => 'events.delete',
                'name' => 'Delete Event',
                'description' => 'Delete events.',
                'route' => 'admin.events.delete',
                'sort' => 4,
            ],
        ]
    ],

    'bookings' => [
        [
            [
                'key' => 'bookings.index',
                'name' => 'Bookings',
                'description' => 'View list of bookings.',
                'route' => 'admin.bookings.index',
                'sort' => 1,
            ],
            [
                'key' => 'bookings.store',
                'name' => 'Create Booking',
                'description' => 'Create new bookings.',
                'route' => 'admin.bookings.store',
                'sort' => 2,
            ],
            [
                'key' => 'bookings.update',
                'name' => 'Update Booking',
                'description' => 'Update existing bookings.',
                'route' => [
                    'admin.bookings.update',
                    'admin.bookings.edit',
                ],
                'sort' => 3,
            ],
            [
                'key' => 'bookings.delete',
                'name' => 'Delete Booking',
                'description' => 'Delete bookings.',
                'route' => 'admin.bookings.delete',
                'sort' => 4,
            ],
        ]
    ],

    // 'payments' => [
    //     [
    //         [
    //             'key' => 'payments.index',
    //             'name' => 'Payments',
    //             'description' => 'View transactions and payments.',
    //             'route' => 'admin.payments.index',
    //             'sort' => 1,
    //         ],
    //         [
    //             'key' => 'payments.store',
    //             'name' => 'Create Payment',
    //             'description' => 'Record new payments.',
    //             'route' => 'admin.payments.store',
    //             'sort' => 2,
    //         ],
    //         [
    //             'key' => 'payments.update',
    //             'name' => 'Update Payment',
    //             'description' => 'Update existing payment records.',
    //             'route' => [
    //                 'admin.payments.update',
    //                 'admin.payments.edit',
    //             ],
    //             'sort' => 3,
    //         ],
    //     ]
    // ],

    'promo_codes' => [
        [
            [
                'key' => 'promo_codes.index',
                'name' => 'Promo Codes',
                'description' => 'View list of promo codes.',
                'route' => 'admin.promo_codes.index',
                'sort' => 1,
            ],
            [
                'key' => 'promo_codes.store',
                'name' => 'Create Promo Code',
                'description' => 'Create new promo codes.',
                'route' => 'admin.promo_codes.store',
                'sort' => 2,
            ],
            [
                'key' => 'promo_codes.update',
                'name' => 'Update Promo Code',
                'description' => 'Update existing promo codes.',
                'route' => [
                    'admin.promo_codes.update',
                    'admin.promo_codes.edit',
                ],
                'sort' => 3,
            ],
            [
                'key' => 'promo_codes.delete',
                'name' => 'Delete Promo Code',
                'description' => 'Delete promo codes.',
                'route' => 'admin.promo_codes.delete',
                'sort' => 4,
            ],
        ]
    ],

    'notifications' => [
        [
            [
                'key' => 'notifications.index',
                'name' => 'Notifications',
                'description' => 'View system notifications.',
                'route' => 'admin.notifications.index',
                'sort' => 1,
            ],
            [
                'key' => 'notifications.store',
                'name' => 'Create Notification',
                'description' => 'Send new notifications.',
                'route' => 'admin.notifications.store',
                'sort' => 2,
            ],
        ]
    ],

    'reviews' => [
        [
            [
                'key' => 'reviews.index',
                'name' => 'Reviews',
                'description' => 'View user reviews.',
                'route' => 'admin.reviews.index',
                'sort' => 1,
            ],
            [
                'key' => 'reviews.delete',
                'name' => 'Delete Review',
                'description' => 'Delete user reviews.',
                'route' => 'admin.reviews.delete',
                'sort' => 2,
            ],
        ]
    ],

    'settings' => [
        [
            [
                'key' => 'settings.index',
                'name' => 'Settings',
                'description' => 'View application settings.',
                'route' => 'admin.settings.index',
                'sort' => 1,
            ],
            [
                'key' => 'settings.store',
                'name' => 'Update Settings',
                'description' => 'Update application settings.',
                'route' => 'admin.settings.store',
                'sort' => 2,
            ],
        ]
    ],

    'audit_logs' => [
        [
            [
                'key' => 'audit_logs.index',
                'name' => 'Audit Logs',
                'description' => 'View system audit logs.',
                'route' => 'admin.audit_logs.index',
                'sort' => 1,
            ],
        ]
    ],

];