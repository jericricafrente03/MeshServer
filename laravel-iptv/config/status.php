<?php
$roomStatus = [
    [
        'id'            => 1,
        'name'          => 'Vaccant', 
        'description'   => '',
        'bg_color'      => '#15a0a3',
        'color'         => '#FFFFFF',
        'icon'          => 'fa-solid fa-door-open',
        'sort'          => 1,
        'category'      => 'room_status',
    ],
    [
        'id'            => 2,
        'name'          => 'Occupied', 
        'description'   => '',
        'bg_color'      => '#db3d5c',
        'color'         => '#FFFFFF',
        'icon'          => 'fa-solid fa-door-closed',
        'sort'          => 2,
        'category'      => 'room_status',
    ],
    [
        'id'            => 3,
        'name'          => 'Maintenance', 
        'description'   => '',
        'bg_color'      => '#edba21',
        'color'         => '#FFFFFF',
        'icon'          => 'fa-solid fa-screwdriver-wrench',
        'sort'          => 3,
        'category'      => 'room_status',
    ],
    [
        'id'            => 4,
        'name'          => 'Repair', 
        'description'   => '',
        'bg_color'      => '#507fb5',
        'color'         => '#FFFFFF',
        'icon'          => 'fa-solid fa-person-digging',
        'sort'          => 4,
        'category'      => 'room_status',
    ],
];

$guestBillingStatus = [
    [
        'id'            => 11,
        'name'          => 'New Order', 
        'description'   => '',
        'bg_color'      => '#8833ff',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'icon'          => '',
        'sort'          => 1,
        'category'      => 'guest_billing_status',
    ],
    [
        'id'            => 12,
        'name'          => 'Pending', 
        'description'   => '',
        'bg_color'      => '#ffc107',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 2,
        'category'      => 'guest_billing_status',
    ],
    [
        'id'            => 13,
        'name'          => 'Processing', 
        'description'   => '',
        'bg_color'      => '#0dcaf0',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 3,
        'category'      => 'guest_billing_status',
    ],
    [
        'id'            => 14,
        'name'          => 'Delivered', 
        'description'   => '',
        'bg_color'      => '#15ca20',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 4,
        'category'      => 'guest_billing_status',
    ],
    [
        'id'            => 15,
        'name'          => 'Cancelled', 
        'description'   => '',
        'bg_color'      => '#fd3550',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 4,
        'category'      => 'guest_billing_status',
    ],
];

$regularMessagesType = [
    [
        'id'            => 21,
        'name'          => 'Single', 
        'description'   => '',
        'bg_color'      => '#198754',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'icon'          => '',
        'sort'          => 1,
        'category'      => 'regular_messages_type',
    ],
    [
        'id'            => 22,
        'name'          => 'Group', 
        'description'   => '',
        'bg_color'      => '#0d6efd',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 2,
        'category'      => 'regular_messages_type',
    ],
    [
        'id'            => 23,
        'name'          => 'All', 
        'description'   => '',
        'bg_color'      => '#dc3545',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 3,
        'category'      => 'regular_messages_type',
    ],
];

$regularMessagesStatus = [
    [
        'id'            => 31,
        'name'          => 'New', 
        'description'   => '',
        'bg_color'      => '#8833ff',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'icon'          => '',
        'sort'          => 1,
        'category'      => 'regular_messages_status',
    ],
    [
        'id'            => 32,
        'name'          => 'Delivered', 
        'description'   => '',
        'bg_color'      => '#ffc107',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 2,
        'category'      => 'regular_messages_status',
    ],
    [
        'id'            => 33,
        'name'          => 'Seen', 
        'description'   => '',
        'bg_color'      => '#0dcaf0',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 3,
        'category'      => 'regular_messages_status',
    ],
    [
        'id'            => 34,
        'name'          => 'Deleted', 
        'description'   => '',
        'bg_color'      => '#fd3550',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 4,
        'category'      => 'regular_messages_status',
    ],
];

$broadcastMessagesGroupType = [
    [
        'id'            => 41,
        'name'          => 'Group', 
        'description'   => '',
        'bg_color'      => '#0d6efd',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 1,
        'category'      => 'broadcast_messages_group_type',
    ],
    [
        'id'            => 42,
        'name'          => 'All', 
        'description'   => '',
        'bg_color'      => '#dc3545',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 2,
        'category'      => 'broadcast_messages_group_type',
    ],
];

$broadcastMessagesType = [
    [
        'id'            => 46,
        'name'          => 'Ticker', 
        'description'   => '',
        'bg_color'      => '#198754',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'icon'          => '',
        'sort'          => 1,
        'category'      => 'broadcast_messages_type',
    ],
    [
        'id'            => 47,
        'name'          => 'Advertisement', 
        'description'   => '',
        'bg_color'      => '#0d6efd',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 2,
        'category'      => 'broadcast_messages_type',
    ],
    [
        'id'            => 48,
        'name'          => 'Emergency', 
        'description'   => '',
        'bg_color'      => '#dc3545',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 3,
        'category'      => 'broadcast_messages_type',
    ],
];

$analyticType = [
    [
        'id'            => 51,
        'name'          => 'Application', 
        'description'   => '',
        'bg_color'      => '#198754',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'icon'          => '',
        'sort'          => 1,
        'category'      => 'analytics_type',
    ],
    [
        'id'            => 52,
        'name'          => 'Tv', 
        'description'   => '',
        'bg_color'      => '#0d6efd',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 2,
        'category'      => 'analytics_type',
    ],

];

$languageType = [
    [
        'id'            => 71,
        'name'          => 'English', 
        'description'   => '',
        'bg_color'      => '#198754',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'icon'          => '',
        'sort'          => 1,
        'category'      => 'language_type',
    ],
    [
        'id'            => 72,
        'name'          => 'Chinese', 
        'description'   => '',
        'bg_color'      => '#0d6efd',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 2,
        'category'      => 'language_type',
    ],
    [
        'id'            => 73,
        'name'          => 'Korean', 
        'description'   => '',
        'bg_color'      => '#0d6efd',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 2,
        'category'      => 'language_type',
    ],
    [
        'id'            => 74,
        'name'          => 'Japanese', 
        'description'   => '',
        'bg_color'      => '#0d6efd',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 2,
        'category'      => 'language_type',
    ],
    [
        'id'            => 75,
        'name'          => 'Arabic', 
        'description'   => '',
        'bg_color'      => '#dc3545',
        'color'         => '#FFFFFF',
        'icon'          => '',
        'sort'          => 3,
        'category'      => 'language_type',
    ],
];

return [
    /****************
     * FOR GENERAL eg. like room statuses w/c are inside general menu pages
     ***************/
    'general' => [
        $roomStatus
        , $guestBillingStatus 
        , $regularMessagesType
        , $regularMessagesStatus
        , $broadcastMessagesType
        , $broadcastMessagesGroupType
        , $analyticType
        , $languageType
    ],
    /**********************
     * FOR SYSTEM SETTINGS eg. like user status w/c are outside general menu pages
     *********************/
    'settings' => [

    ]
];
