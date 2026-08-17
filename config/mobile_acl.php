<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mobile Access Control List (ACL)
    |--------------------------------------------------------------------------
    |
    | All ACLs related to the admin panel are defined here. 
    */
    'booking' => [
        [
            'key' => 'can_booking_check_in',
            'name' => 'Booking Check In',
            'description' => 'Can booking check in',
            'icon' => 'pi pi-users',
            'route' => ['api.qrcode.booking_checkin'],
            'sort' => 1,
        ],
    ],

    'qr_code' => [
        [
            'key' => 'can_qr_scan',
            'name' => 'Can Qr scan',
            'description' => 'Can Qr scan',
            'icon' => 'pi pi-users',
            'route' => ['api.qrcode.scan'],
            'sort' => 1,
        ],
    ]
];
