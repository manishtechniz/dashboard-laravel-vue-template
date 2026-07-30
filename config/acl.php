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
            'key' => 'dashboard',
            'name' => 'Dashboard',
            'description' => 'View the main admin dashboard.',
            'route' => 'admin.dashboard',
            'icon' => 'pi pi-home',
            'sort' => 1,
        ],
    ],

    'roles' => [
        [
            'key' => 'roles.index',
            'name' => 'Roles',
            'description' => 'View list of admin roles.',
            'route' => 'admin.roles.index',
            'icon' => 'pi pi-shield',
            'sort' => 1,
        ],
        [
            'key' => 'roles.store',
            'name' => 'Create Role',
            'description' => 'Create new admin roles.',
            'route' => 'admin.roles.store',
            'icon' => 'pi pi-shield',
            'sort' => 2,
        ],
        [
            'key' => 'roles.update',
            'name' => 'Update Role',
            'description' => 'Update existing admin roles.',
            'route' => 'admin.roles.update',
            'icon' => 'pi pi-shield',
            'sort' => 3,
        ],
        [
            'key' => 'roles.delete',
            'name' => 'Delete Role',
            'description' => 'Delete admin roles.',
            'route' => 'admin.roles.delete',
            'icon' => 'pi pi-shield',
            'sort' => 4,
        ],
    ],

    'users' => [
        [
            'key' => 'users.index',
            'name' => 'Users',
            'description' => 'View list of system users.',
            'route' => 'admin.users.index',
            'icon' => 'pi pi-users',
            'sort' => 1,
        ],
        [
            'key' => 'users.store',
            'name' => 'Create User',
            'description' => 'Create new system users.',
            'route' => 'admin.users.store',
            'icon' => 'pi pi-users',
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
            'icon' => 'pi pi-users',
            'sort' => 3,
        ],
        [
            'key' => 'users.delete',
            'name' => 'Delete User',
            'description' => 'Delete system users.',
            'route' => 'admin.users.delete',
            'icon' => 'pi pi-users',
            'sort' => 4,
        ],
    ],

    'clients' => [
        [
            'key' => 'clients.index',
            'name' => 'Clients',
            'description' => 'View list of clients.',
            'route' => 'admin.clients.index',
            'icon' => 'pi pi-users',
            'sort' => 1,
        ],
        [
            'key' => 'clients.store',
            'name' => 'Create Client',
            'description' => 'Create new clients.',
            'route' => 'admin.clients.store',
            'icon' => 'pi pi-users',
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
            'icon' => 'pi pi-users',
            'sort' => 3,
        ],
        [
            'key' => 'clients.delete',
            'name' => 'Delete Client',
            'description' => 'Delete clients.',
            'route' => 'admin.clients.delete',
            'icon' => 'pi pi-users',
            'sort' => 4,
        ],
    ],

    'clubs' => [
        // Club Management
        [
            'key' => 'clubs.index',
            'name' => 'Clubs',
            'description' => 'View list of clubs and branches.',
            'route' => 'admin.clubs.index',
            'icon' => 'pi pi-building',
            'sort' => 1,
        ],
        [
            'key' => 'clubs.store',
            'name' => 'Create Club',
            'description' => 'Create new clubs.',
            'route' => 'admin.clubs.store_club',
            'icon' => 'pi pi-building',
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
            'icon' => 'pi pi-building',
            'sort' => 3,
        ],
        [
            'key' => 'clubs.delete',
            'name' => 'Delete Club',
            'description' => 'Delete clubs.',
            'route' => 'admin.clubs.delete_club',
            'icon' => 'pi pi-building',
            'sort' => 4,
        ],
    ],

    'tables' => [
        [
            'key' => 'tables.index',
            'name' => 'Tables',
            'description' => 'View list of tables.',
            'route' => 'admin.tables.index',
            'icon' => 'pi pi-table',
            'sort' => 1,
        ],
        [
            'key' => 'tables.store',
            'name' => 'Create Table',
            'description' => 'Create new tables.',
            'route' => 'admin.tables.store',
            'icon' => 'pi pi-table',
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
            'icon' => 'pi pi-table',
            'sort' => 3,
        ],
        [
            'key' => 'tables.delete',
            'name' => 'Delete Table',
            'description' => 'Delete tables.',
            'route' => 'admin.tables.delete',
            'icon' => 'pi pi-table',
            'sort' => 4,
        ],
    ],

    'events' => [
        [
            'key' => 'events.index',
            'name' => 'Events',
            'description' => 'View list of events.',
            'route' => 'admin.events.index',
            'icon' => 'pi pi-calendar',
            'sort' => 1,
        ],
        [
            'key' => 'events.store',
            'name' => 'Create Event',
            'description' => 'Create new events.',
            'route' => 'admin.events.store',
            'icon' => 'pi pi-calendar',
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
            'icon' => 'pi pi-calendar',
            'sort' => 3,
        ],
        [
            'key' => 'events.delete',
            'name' => 'Delete Event',
            'description' => 'Delete events.',
            'route' => 'admin.events.delete',
            'icon' => 'pi pi-calendar',
            'sort' => 4,
        ],
    ],

    'bookings' => [
        [
            'key' => 'bookings.index',
            'name' => 'Bookings',
            'description' => 'View list of bookings.',
            'route' => 'admin.bookings.index',
            'icon' => 'pi pi-ticket',
            'sort' => 1,
        ],
        [
            'key' => 'bookings.store',
            'name' => 'Create Booking',
            'description' => 'Create new bookings.',
            'route' => 'admin.bookings.store',
            'icon' => 'pi pi-ticket',
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
            'icon' => 'pi pi-ticket',
            'sort' => 3,
        ],
        [
            'key' => 'bookings.delete',
            'name' => 'Delete Booking',
            'description' => 'Delete bookings.',
            'route' => 'admin.bookings.delete',
            'icon' => 'pi pi-ticket',
            'sort' => 4,
        ],
    ],

    'promo_codes' => [
        [
            'key' => 'promo_codes.index',
            'name' => 'Promo Codes',
            'description' => 'View list of promo codes.',
            'route' => 'admin.promo_codes.index',
            'icon' => 'pi pi-percentage',
            'sort' => 1,
        ],
        [
            'key' => 'promo_codes.store',
            'name' => 'Create Promo Code',
            'description' => 'Create new promo codes.',
            'route' => 'admin.promo_codes.store',
            'icon' => 'pi pi-percentage',
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
            'icon' => 'pi pi-percentage',
            'sort' => 3,
        ],
        [
            'key' => 'promo_codes.delete',
            'name' => 'Delete Promo Code',
            'description' => 'Delete promo codes.',
            'route' => 'admin.promo_codes.delete',
            'icon' => 'pi pi-percentage',
            'sort' => 4,
        ],
    ],

    'notifications' => [
        [
            'key' => 'notifications.index',
            'name' => 'Notifications',
            'description' => 'View system notifications.',
            'route' => 'admin.notifications.index',
            'icon' => 'pi pi-bell',
            'sort' => 1,
        ],
        [
            'key' => 'notifications.store',
            'name' => 'Create Notification',
            'description' => 'Send new notifications.',
            'route' => 'admin.notifications.store',
            'icon' => 'pi pi-bell',
            'sort' => 2,
        ],
    ],

    'reviews' => [
        [
            'key' => 'reviews.index',
            'name' => 'Reviews',
            'description' => 'View user reviews.',
            'route' => 'admin.reviews.index',
            'icon' => 'pi pi-star',
            'sort' => 1,
        ],
        [
            'key' => 'reviews.delete',
            'name' => 'Delete Review',
            'description' => 'Delete user reviews.',
            'route' => 'admin.reviews.delete',
            'icon' => 'pi pi-star',
            'sort' => 2,
        ],
    ],

    'settings' => [
        [
            'key' => 'settings.index',
            'name' => 'Settings',
            'description' => 'View application settings.',
            'route' => 'admin.settings.index',
            'icon' => 'pi pi-cog',
            'sort' => 1,
        ],
        [
            'key' => 'settings.store',
            'name' => 'Update Settings',
            'description' => 'Update application settings.',
            'route' => 'admin.settings.store',
            'icon' => 'pi pi-cog',
            'sort' => 2,
        ],
    ],

    'audit_logs' => [
        [
            'key' => 'audit_logs.index',
            'name' => 'Audit Logs',
            'description' => 'View system audit logs.',
            'route' => 'admin.audit_logs.index',
            'icon' => 'pi pi-list',
            'sort' => 1,
        ],
    ],

    'profile' => [
        [
            'key' => 'profile.index',
            'name' => 'View Profile',
            'visibility' => 'hidden',
            'description' => 'View admin profile details.',
            'route' => 'admin.profile.index',
            'icon' => 'pi pi-user',
            'sort' => 1,
        ],
        [
            'key' => 'profile.update',
            'name' => 'Update Profile',
            'description' => 'Update admin profile information.',
            'route' => 'admin.profile.update',
            'icon' => 'pi pi-user',
            'sort' => 2,
        ],
    ],

];
