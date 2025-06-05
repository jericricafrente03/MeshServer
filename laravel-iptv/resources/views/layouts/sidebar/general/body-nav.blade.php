<!-- Analytics -->
@canany(
    array_merge(
        array_keys($menuGeneralGroupPermissions, 'analytics')
    )
)
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="fa-solid fa-chart-simple fa-sm"></i></div>
            <div class="menu-title">Analytics</div>
        </a>
        <ul>
            @canany(['analytics.tv'])
                <li>
                    <a href="/analytics/tv-channels"><i class="bx bx-right-arrow-alt"></i>Tv Channels</a>
                </li>
            @endcanany
            @canany(['analytics.regular_messages'])
                <li>
                    <a href="/analytics/regular-messaging"><i class="bx bx-right-arrow-alt"></i>Regular Messaging</a>
                </li>
            @endcanany
            @canany(['analytics.broadcast_messages'])
                <li>
                    <a href="/analytics/broadcast-messaging"><i class="bx bx-right-arrow-alt"></i>Broadcast Messaging</a>
                </li>
            @endcanany
            @canany(['analytics.fnbs'])
                <li>
                    <a href="/analytics/fnbs"><i class="bx bx-right-arrow-alt"></i>FnB</a>
                </li>
            @endcanany
            @canany(['analytics.item_requests'])
                <li>
                    <a href="/analytics/item-requests"><i class="bx bx-right-arrow-alt"></i>Item Requests</a>
                </li>
            @endcanany
            @canany(['analytics.service_requests'])
                <li>
                    <a href="/analytics/service-requests"><i class="bx bx-right-arrow-alt"></i>Service Requests</a>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany

<!-- Devices -->
@canany(array_merge(array_keys($menuGeneralGroupPermissions, 'devices'),array_keys($menuGeneralGroupPermissions, 'device_group'), array_keys($menuGeneralGroupPermissions, 'device_monitor')))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="fa-regular fa-hard-drive fa-sm"></i></div>
            <div class="menu-title">Devices</div>
        </a>
        <ul>
            @canany(['devices.index'])
                <li>
                    <a href="/devices"><i class="bx bx-right-arrow-alt"></i>Devices</a>
                </li>
            @endcanany
            @canany(['device_group.index'])
                <li>
                    <a href="/device_group"><i class="bx bx-right-arrow-alt"></i>Device Groups</a>
                </li>
            @endcanany
            @canany(['device_monitor.index'])
                <li>
                    <a href="/device_monitor"><i class="bx bx-right-arrow-alt"></i>Device Monitor</a>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany

<!-- TV -->
@canany(
    array_merge(
        array_keys($menuGeneralGroupPermissions, 'tv_channels'),
        array_keys($menuGeneralGroupPermissions, 'tv_channel_categories')
    )
)
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="fa-solid fa-tv fa-sm"></i></div>
            <div class="menu-title">TV</div>
        </a>
        <ul>
            @canany(['tv_channels.index'])
                <li>
                    <a href="/tv-channels"><i class="bx bx-right-arrow-alt"></i>TV Channels</a>
                </li>
            @endcanany
            @canany(['tv_channel_categories.index'])
                <li>
                    <a href="/tv-channel-categories"><i class="bx bx-right-arrow-alt"></i>TV Channel Categories</a>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany

<!-- Hospitality -->
@canany(
    array_merge(
        array_keys($menuGeneralGroupPermissions, 'facilities'),
        array_keys($menuGeneralGroupPermissions, 'facility_categories'),
        array_keys($menuGeneralGroupPermissions, 'fnb'), 
        array_keys($menuGeneralGroupPermissions, 'fnb_categories'),
        array_keys($menuGeneralGroupPermissions, 'hotel_infos'),
        array_keys($menuGeneralGroupPermissions, 'item_requests'),
        array_keys($menuGeneralGroupPermissions, 'service_requests')
))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="fa-solid fa-bed fa-xs"></i></div>
            <div class="menu-title">Hospitality</div>
        </a>
        <ul>
            @canany(['facilities.index'])
                <li>
                    <a href="/facilities"><i class="bx bx-right-arrow-alt"></i>Facilities</a>
                </li>
            @endcanany
            @canany(['facility_categories.index'])
                <li>
                    <a href="/facility-categories"><i class="bx bx-right-arrow-alt"></i>Facility Categories</a>
                </li>
            @endcanany
            @canany(['fnb.index'])
                <li>
                    <a href="/fnb"><i class="bx bx-right-arrow-alt"></i>FnB</a>
                </li>
            @endcanany
            @canany(['fnb_categories.index'])
                <li>
                    <a href="/fnb-categories"><i class="bx bx-right-arrow-alt"></i>FnB Categories</a>
                </li>
            @endcanany
            @canany(['hotel_infos.index'])
                <li>
                    <a href="/hotel-infos"><i class="bx bx-right-arrow-alt"></i>Hotel Infos</a>
                </li>
            @endcanany
            @canany(['item_requests.index'])
                <li>
                    <a href="/item-requests"><i class="bx bx-right-arrow-alt"></i>Item Requests</a>
                </li>
            @endcanany
            @canany(['service_requests.index'])
                <li>
                    <a href="/service-requests"><i class="bx bx-right-arrow-alt"></i>Service Requests</a>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany

<!-- Guests -->
@canany(
    array_merge(
    array_keys($menuGeneralGroupPermissions, 'rooms'), 
    array_keys($menuGeneralGroupPermissions, 'room_assignments'), 
    array_keys($menuGeneralGroupPermissions, 'room_categories'), 
    array_keys($menuGeneralGroupPermissions, 'guests'), 
    array_keys($menuGeneralGroupPermissions, 'guest_billings')
))
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="fa-solid fa-users fa-xs"></i></div>
            <div class="menu-title">Guests</div>
        </a>
        <ul>
            @canany(['room_assignments.index'])
                <li>
                    <a href="/room-assignments"><i class="bx bx-right-arrow-alt"></i>Room Assignments</a>
                </li>
            @endcanany
            @canany(['room.index'])
                <li>
                    <a href="/rooms"><i class="bx bx-right-arrow-alt"></i>Rooms</a>
                </li>
            @endcanany
            @canany(['room_categories.index'])
                <li>
                    <a href="/room-categories"><i class="bx bx-right-arrow-alt"></i>Room Categories</a>
                </li>
            @endcanany
            @canany(['guests.index'])
                <li>
                    <a href="/guests"><i class="bx bx-right-arrow-alt"></i>Guests</a>
                </li>
            @endcanany
            @canany(['guest_billings.index'])
                <li>
                    <a href="/guest-billings"><i class="bx bx-right-arrow-alt"></i>Guest Billings</a>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany

<!-- Messages -->
@canany(
    array_merge(
        array_keys($menuGeneralGroupPermissions, 'regular_messages'),
        array_keys($menuGeneralGroupPermissions, 'tickers'), 
        array_keys($menuGeneralGroupPermissions, 'advertisements'),
        array_keys($menuGeneralGroupPermissions, 'emergencies'),
    )
)
    <li>
        <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="fa-solid fa-envelope fa-sm"></i></div>
            <div class="menu-title">Messages</div>
        </a>
        <ul>
            @canany(['regular_messages.index'])
                <li>
                    <a href="/regular-messages"><i class="bx bx-right-arrow-alt"></i>Regular Messaging</a>
                </li>
            @endcanany
            @canany(
                array_merge(
                    array_keys($menuGeneralGroupPermissions, 'tickers'),
                    array_keys($menuGeneralGroupPermissions, 'advertisements'),
                    array_keys($menuGeneralGroupPermissions, 'emergencies'),
                )
            )
                <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Broadcast Messaging</a>
                    <ul>
                        @canany(['tickers.index'])
                            <li>
                                <a href="/tickers"><i class="bx bx-right-arrow-alt"></i>Ticker</a>
                            </li>
                        @endcanany
                        @canany(['advertisements.index'])
                            <li>
                                <a href="/advertisements"><i class="bx bx-right-arrow-alt"></i>Advertisement</a>
                            </li>
                        @endcanany
                        @canany(['emergencies.index'])
                            <li>
                                <a href="/emergencies"><i class="bx bx-right-arrow-alt"></i>Emergency</a>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany

<!-- Video Ads-->
@canany(
    array_merge(
        array_keys($menuGeneralGroupPermissions, 'regular_messages'),
    )
)
    <li class="sidebar-default-nav">
        <a href="/video-ads">
            <div class="parent-icon"><i class='fa-solid fa-video fa-sm'></i>
            </div>
            <div class="menu-title">Video Ads</div>
        </a>
    </li>
@endcanany
    

<!-- -->