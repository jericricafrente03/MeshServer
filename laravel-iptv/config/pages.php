<?php

return [
    /****************
     * System Users
     ***************/

    //  Users
    ['name'=>'user.index','display_name'=>'User List','division_name'=>'system_settings','group_name'=>'user'],
    ['name'=>'user.create','display_name'=>'Create User','division_name'=>'system_settings','group_name'=>'user'],
    ['name'=>'user.update','display_name'=>'Update User','division_name'=>'system_settings','group_name'=>'user'],
    ['name'=>'user.delete','display_name'=>'Delete User','division_name'=>'system_settings','group_name'=>'user'],

    // Roles
    ['name'=>'role.index','display_name'=>'Role List','division_name'=>'system_settings','group_name'=>'role'],
    ['name'=>'role.create','display_name'=>'Create Role','division_name'=>'system_settings','group_name'=>'role'],
    ['name'=>'role.update','display_name'=>'Update Role','division_name'=>'system_settings','group_name'=>'role'],
    ['name'=>'role.delete','display_name'=>'Delete Role','division_name'=>'system_settings','group_name'=>'role'],

    // RBAC
    ['name'=>'rbac.index','display_name'=>'Role-Based Access Control List','division_name'=>'system_settings','group_name'=>'rbac'],

    // Log
    ['name'=>'log.index','display_name'=>'Log List','division_name'=>'system_settings','group_name'=>'log'],
    ['name'=>'log.show','display_name'=>'View Log Description','division_name'=>'system_settings','group_name'=>'log'],
   
    /****************
     * System Settings
     ***************/

    // System Config
    ['name'=>'system_config.index','display_name'=>'System Config Page','division_name'=>'system_settings','group_name'=>'system_config'],
    ['name'=>'system_config.update','display_name'=>'Update System Config','division_name'=>'system_settings','group_name'=>'system_config'],

    // Default Apps
    ['name'=>'default_apps.index','display_name'=>'Default Apps List','division_name'=>'system_settings','group_name'=>'default_apps'],
    ['name'=>'default_apps.create','display_name'=>'Create Default App','division_name'=>'system_settings','group_name'=>'default_apps'],
    ['name'=>'default_apps.edit','display_name'=>'Edit Default App','division_name'=>'system_settings','group_name'=>'default_apps'],
    ['name'=>'default_apps.delete','display_name'=>'Delete Default App','division_name'=>'system_settings','group_name'=>'default_apps'],
    ['name'=>'default_apps.enable','display_name'=>'Toggle Default App Enable/Disable','division_name'=>'system_settings','group_name'=>'default_apps'],

    // Zones
    ['name'=>'zones.index','display_name'=>'Zone List','division_name'=>'system_settings','group_name'=>'zones'],
    ['name'=>'zones.create','display_name'=>'Create Zone','division_name'=>'system_settings','group_name'=>'zones'],
    ['name'=>'zones.edit','display_name'=>'Edit Zone','division_name'=>'system_settings','group_name'=>'zones'],
    ['name'=>'zones.delete','display_name'=>'Delete Zone','division_name'=>'system_settings','group_name'=>'zones'],
   
    // Themes
    ['name'=>'themes.index','display_name'=>'Theme List','division_name'=>'system_settings','group_name'=>'themes'],
    ['name'=>'themes.create','display_name'=>'Create Theme','division_name'=>'system_settings','group_name'=>'themes'],
    ['name'=>'themes.default','display_name'=>'Toggle Theme as Default','division_name'=>'system_settings','group_name'=>'themes'],
    ['name'=>'themes.edit_theme','display_name'=>'Edit Theme','division_name'=>'system_settings','group_name'=>'themes'],
    ['name'=>'themes.edit_theme_zone','display_name'=>'Edit Theme Zone','division_name'=>'system_settings','group_name'=>'themes'],
    ['name'=>'themes.assign_room','display_name'=>'Assign Room to specific Theme','division_name'=>'system_settings','group_name'=>'themes'],
    ['name'=>'themes.delete','display_name'=>'Delete Theme','division_name'=>'system_settings','group_name'=>'themes'],
    ['name'=>'themes.edit_application','display_name'=>'Edit Application of Theme','division_name'=>'system_settings','group_name'=>'themes'],
    ['name'=>'themes.enable_theme_application','display_name'=>'Toggle Theme Application Enable/Disable','division_name'=>'system_settings','group_name'=>'themes'],

    // Device ADB Manager
    ['name'=>'device_adb_manager.index','display_name'=>'Device ADB Manager Page','division_name'=>'system_settings','group_name'=>'device_adb_manager'],
    ['name'=>'device_adb_manager.group_install','display_name'=>'Install Application in Device ADB Manager Page','division_name'=>'system_settings','group_name'=>'device_adb_manager'],
    ['name'=>'device_adb_manager.group_uninstall','display_name'=>'Uninstall Application in Device ADB Manager Page','division_name'=>'system_settings','group_name'=>'device_adb_manager'],
    ['name'=>'device_adb_manager.settings','display_name'=>'Edit ADB Manager Settings','division_name'=>'system_settings','group_name'=>'device_adb_manager'],
    ['name'=>'device_adb_manager.screencapture','display_name'=>'ADB screen capture specific IP Address','division_name'=>'system_settings','group_name'=>'device_adb_manager'],
    ['name'=>'device_adb_manager.screenrecord','display_name'=>'ADB screen record specific IP Address','division_name'=>'system_settings','group_name'=>'device_adb_manager'],

    // Device ADB Screen Capture
    ['name'=>'device_adb_screen_capture.index','display_name'=>'Device ADB Files Screen Capture List','division_name'=>'system_settings','group_name'=>'device_adb_screen_capture'],
    ['name'=>'device_adb_screen_capture.delete','display_name'=>'Delete selected Device ADB Files Screen Capture','division_name'=>'system_settings','group_name'=>'device_adb_screen_capture'],

    // Device ADB Screen Record
    ['name'=>'device_adb_screen_record.index','display_name'=>'Device ADB Files Screen Record List','division_name'=>'system_settings','group_name'=>'device_adb_screen_record'],
    ['name'=>'device_adb_screen_record.delete','display_name'=>'Delete selected Device ADB Files Screen Record','division_name'=>'system_settings','group_name'=>'device_adb_screen_record'],
    
    // Device ADB APK
    ['name'=>'device_adb_apk.index','display_name'=>'Device ADB APK List','division_name'=>'system_settings','group_name'=>'device_adb_apk'],
    ['name'=>'device_adb_apk.delete','display_name'=>'Delete selected Device ADB APK','division_name'=>'system_settings','group_name'=>'device_adb_apk'],
    ['name'=>'device_adb_apk.create','display_name'=>'Create Device ADB APK','division_name'=>'system_settings','group_name'=>'device_adb_apk'],
    

    /*********************
     * Dashboard
     *********************/
    ['name'=>'dashboard.index','display_name'=>'View Dashboard','division_name'=>'general','group_name'=>'dashboard'],

    //Analytics
    ['name'=>'analytics.apps','display_name'=>'View Analytics for Apps','division_name'=>'general','group_name'=>'analytics'],
    ['name'=>'analytics.regular_messages','display_name'=>'View Analytics for Regular Messaging','division_name'=>'general','group_name'=>'analytics'],
    ['name'=>'analytics.broadcast_messages','display_name'=>'View Analytics for Broadcast Messaging','division_name'=>'general','group_name'=>'analytics'],
    ['name'=>'analytics.devices','display_name'=>'View Analytics for Devices','division_name'=>'general','group_name'=>'analytics'],
    ['name'=>'analytics.room_assignments','display_name'=>'View Analytics for Room Assignments','division_name'=>'general','group_name'=>'analytics'],
    ['name'=>'analytics.tv','display_name'=>'View Analytics for TV Channels','division_name'=>'general','group_name'=>'analytics'],
    ['name'=>'analytics.rooms','display_name'=>'View Analytics for Rooms','division_name'=>'general','group_name'=>'analytics'],
    ['name'=>'analytics.fnbs','display_name'=>'View Analytics for FNBs','division_name'=>'general','group_name'=>'analytics'],
    ['name'=>'analytics.item_requests','display_name'=>'View Analytics for Item Requests','division_name'=>'general','group_name'=>'analytics'],
    ['name'=>'analytics.service_requests','display_name'=>'View Analytics for Service Requests','division_name'=>'general','group_name'=>'analytics'],
    
    /*********************
     * Body - Devices
     *********************/

    //Devices
    ['name'=>'devices.index','display_name'=>'Devices List','division_name'=>'general','group_name'=>'devices'],
    ['name'=>'devices.view','display_name'=>'View Device Record','division_name'=>'general','group_name'=>'devices'],
    ['name'=>'devices.update','display_name'=>'Update Device Record','division_name'=>'general','group_name'=>'devices'],
    ['name'=>'devices.delete','display_name'=>'Delete Device Record','division_name'=>'general','group_name'=>'devices'],
    ['name'=>'devices.adb','display_name'=>'Some ABD Command for Device','division_name'=>'general','group_name'=>'devices'],
    ['name'=>'devices.detect_devices','display_name'=>'Detect Devices Function','division_name'=>'general','group_name'=>'devices'],

    //Device Group
    ['name'=>'device_group.index','display_name'=>'Device Group List','division_name'=>'general','group_name'=>'device_group'],
    ['name'=>'device_group.create','display_name'=>'Create Device Group','division_name'=>'general','group_name'=>'device_group'],
    ['name'=>'device_group.view','display_name'=>'View Device Group','division_name'=>'general','group_name'=>'device_group'],
    ['name'=>'device_group.edit','display_name'=>'Edit Device Group','division_name'=>'general','group_name'=>'device_group'],
    ['name'=>'device_group.delete','display_name'=>'Delete Device Group','division_name'=>'general','group_name'=>'device_group'],
    ['name'=>'device_group.change_order','display_name'=>'Change Order Device Group','division_name'=>'general','group_name'=>'device_group'],
    
    //Device Monitor
    ['name'=>'device_monitor.index','display_name'=>'Device Monitor Index Page','division_name'=>'general','group_name'=>'device_monitor'],

    //Rooms
    ['name'=>'room.index','display_name'=>'Room List','division_name'=>'general','group_name'=>'rooms'],
    ['name'=>'room.create','display_name'=>'Create Room Record','division_name'=>'general','group_name'=>'rooms'],
    ['name'=>'room.view','display_name'=>'View Room Record','division_name'=>'general','group_name'=>'rooms'],
    ['name'=>'room.edit','display_name'=>'Edit Room Record','division_name'=>'general','group_name'=>'rooms'],
    ['name'=>'room.delete','display_name'=>'Delete Room Record','division_name'=>'general','group_name'=>'rooms'],

    //Room Categories
    ['name'=>'room_categories.index','display_name'=>'Room Category List','division_name'=>'general','group_name'=>'room_categories'],
    ['name'=>'room_categories.create','display_name'=>'Create Room Category Record','division_name'=>'general','group_name'=>'room_categories'],
    ['name'=>'room_categories.view','display_name'=>'View Room Category Record','division_name'=>'general','group_name'=>'room_categories'],
    ['name'=>'room_categories.edit','display_name'=>'Edit Room Category Record','division_name'=>'general','group_name'=>'room_categories'],
    ['name'=>'room_categories.delete','display_name'=>'Delete Room Category Record','division_name'=>'general','group_name'=>'room_categories'],
    ['name'=>'room_categories.change_order','display_name'=>'Change Order Room Category','division_name'=>'general','group_name'=>'room_categories'],

    //Guests
    ['name'=>'guests.index','display_name'=>'Guest List','division_name'=>'general','group_name'=>'guests'],
    ['name'=>'guests.create','display_name'=>'Create Guest Record','division_name'=>'general','group_name'=>'guests'],
    ['name'=>'guests.view','display_name'=>'View Guest Record','division_name'=>'general','group_name'=>'guests'],
    ['name'=>'guests.edit','display_name'=>'Edit Guest Record','division_name'=>'general','group_name'=>'guests'],
    ['name'=>'guests.delete','display_name'=>'Delete Guest Record','division_name'=>'general','group_name'=>'guests'],
    
    //Guest Billings
    ['name'=>'guest_billings.index','display_name'=>'Guest Billing List','division_name'=>'general','group_name'=>'guest_billings'],
    ['name'=>'guest_billings.edit','display_name'=>'Edit Guest Billing Record','division_name'=>'general','group_name'=>'guest_billings'],
    ['name'=>'guest_billings.payment','display_name'=>'Edit Guest Billing Record','division_name'=>'general','group_name'=>'guest_billings'],

    //Room Assignments
    ['name'=>'room_assignments.index','display_name'=>'Room Assignment List','division_name'=>'general','group_name'=>'room_assignments'],
    ['name'=>'room_assignments.create','display_name'=>'Create Room Assignment Record','division_name'=>'general','group_name'=>'room_assignments'],
    ['name'=>'room_assignments.checkout','display_name'=>'Check out Room Assignment Record','division_name'=>'general','group_name'=>'room_assignments'],
    ['name'=>'room_assignments.delete','display_name'=>'Delete Room Assignment Record','division_name'=>'general','group_name'=>'room_assignments'],
    ['name'=>'room_assignments.changeroom','display_name'=>'Changing Room of Room Assignment Record','division_name'=>'general','group_name'=>'room_assignments'],
    ['name'=>'room_assignments.billing','display_name'=>'Control Guest Billing of Room Assignment Record','division_name'=>'general','group_name'=>'room_assignments'],

    //Facilities
    ['name'=>'facilities.index','display_name'=>'Facilities List','division_name'=>'general','group_name'=>'facilities'],
    ['name'=>'facilities.create','display_name'=>'Create Facilities Record','division_name'=>'general','group_name'=>'facilities'],
    ['name'=>'facilities.view','display_name'=>'View Facilities Record','division_name'=>'general','group_name'=>'facilities'],
    ['name'=>'facilities.edit','display_name'=>'Edit Facilities Record','division_name'=>'general','group_name'=>'facilities'],
    ['name'=>'facilities.delete','display_name'=>'Delete Facilities Record','division_name'=>'general','group_name'=>'facilities'],
    ['name'=>'facilities.enable','display_name'=>'Toggle Facilities Enable On/Off','division_name'=>'general','group_name'=>'facilities'],

    //Facility Categories
    ['name'=>'facility_categories.index','display_name'=>'Facility Category List','division_name'=>'general','group_name'=>'facility_categories'],
    ['name'=>'facility_categories.create','display_name'=>'Create Facility Category Record','division_name'=>'general','group_name'=>'facility_categories'],
    ['name'=>'facility_categories.view','display_name'=>'View Facility Category Record','division_name'=>'general','group_name'=>'facility_categories'],
    ['name'=>'facility_categories.edit','display_name'=>'Edit Facility Category Record','division_name'=>'general','group_name'=>'facility_categories'],
    ['name'=>'facility_categories.delete','display_name'=>'Delete Facility Category Record','division_name'=>'general','group_name'=>'facility_categories'],
    ['name'=>'facility_categories.change_order','display_name'=>'Change Order Facility Category','division_name'=>'general','group_name'=>'facility_categories'],

    //FnB
    ['name'=>'fnb.index','display_name'=>'FnB List','division_name'=>'general','group_name'=>'fnb'],
    ['name'=>'fnb.create','display_name'=>'Create FnB Record','division_name'=>'general','group_name'=>'fnb'],
    ['name'=>'fnb.view','display_name'=>'View FnB Record','division_name'=>'general','group_name'=>'fnb'],
    ['name'=>'fnb.edit','display_name'=>'Edit FnB Record','division_name'=>'general','group_name'=>'fnb'],
    ['name'=>'fnb.delete','display_name'=>'Delete FnB Record','division_name'=>'general','group_name'=>'fnb'],
    ['name'=>'fnb.enable','display_name'=>'Toggle FnB Enable On/Off','division_name'=>'general','group_name'=>'fnb'],

    //FnB Categories
    ['name'=>'fnb_categories.index','display_name'=>'FnB Category List','division_name'=>'general','group_name'=>'fnb_categories'],
    ['name'=>'fnb_categories.create','display_name'=>'Create FnB Category Record','division_name'=>'general','group_name'=>'fnb_categories'],
    ['name'=>'fnb_categories.view','display_name'=>'View FnB Category Record','division_name'=>'general','group_name'=>'fnb_categories'],
    ['name'=>'fnb_categories.edit','display_name'=>'Edit FnB Category Record','division_name'=>'general','group_name'=>'fnb_categories'],
    ['name'=>'fnb_categories.delete','display_name'=>'Delete FnB Category Record','division_name'=>'general','group_name'=>'fnb_categories'],
    ['name'=>'fnb_categories.change_order','display_name'=>'Change Order FnB Category','division_name'=>'general','group_name'=>'fnb_categories'],

    //Hotel Infos
    ['name'=>'hotel_infos.index','display_name'=>'Hotel Infos List','division_name'=>'general','group_name'=>'hotel_infos'],
    ['name'=>'hotel_infos.create','display_name'=>'Create Hotel Infos Record','division_name'=>'general','group_name'=>'hotel_infos'],
    ['name'=>'hotel_infos.view','display_name'=>'View Hotel Infos Record','division_name'=>'general','group_name'=>'hotel_infos'],
    ['name'=>'hotel_infos.edit','display_name'=>'Edit Hotel Infos Record','division_name'=>'general','group_name'=>'hotel_infos'],
    ['name'=>'hotel_infos.delete','display_name'=>'Delete Hotel Infos Record','division_name'=>'general','group_name'=>'hotel_infos'],
    ['name'=>'hotel_infos.enable','display_name'=>'Toggle Hotel Infos Enable On/Off','division_name'=>'general','group_name'=>'hotel_infos'],
    ['name'=>'hotel_infos.change_order','display_name'=>'Change Order Hotel Infos','division_name'=>'general','group_name'=>'hotel_infos'],

    //Item Requests
    ['name'=>'item_requests.index','display_name'=>'Item Requests List','division_name'=>'general','group_name'=>'item_requests'],
    ['name'=>'item_requests.create','display_name'=>'Create Item Requests Record','division_name'=>'general','group_name'=>'item_requests'],
    ['name'=>'item_requests.view','display_name'=>'View Item Requests Record','division_name'=>'general','group_name'=>'item_requests'],
    ['name'=>'item_requests.edit','display_name'=>'Edit Item Requests Record','division_name'=>'general','group_name'=>'item_requests'],
    ['name'=>'item_requests.delete','display_name'=>'Delete Item Requests Record','division_name'=>'general','group_name'=>'item_requests'],
    ['name'=>'item_requests.enable','display_name'=>'Toggle Item Requests Enable On/Off','division_name'=>'general','group_name'=>'item_requests'],

    //Service Requests
    ['name'=>'service_requests.index','display_name'=>'Service Requests List','division_name'=>'general','group_name'=>'service_requests'],
    ['name'=>'service_requests.create','display_name'=>'Create Service Requests Record','division_name'=>'general','group_name'=>'service_requests'],
    ['name'=>'service_requests.view','display_name'=>'View Service Requests Record','division_name'=>'general','group_name'=>'service_requests'],
    ['name'=>'service_requests.edit','display_name'=>'Edit Service Requests Record','division_name'=>'general','group_name'=>'service_requests'],
    ['name'=>'service_requests.delete','display_name'=>'Delete Service Requests Record','division_name'=>'general','group_name'=>'service_requests'],
    ['name'=>'service_requests.enable','display_name'=>'Toggle Service Requests Enable On/Off','division_name'=>'general','group_name'=>'service_requests'],

    //TV Channels
    ['name'=>'tv_channels.index','display_name'=>'TV Channel List','division_name'=>'general','group_name'=>'tv_channels'],
    ['name'=>'tv_channels.create','display_name'=>'Create TV Channel Record','division_name'=>'general','group_name'=>'tv_channels'],
    ['name'=>'tv_channels.view','display_name'=>'View TV Channel Record','division_name'=>'general','group_name'=>'tv_channels'],
    ['name'=>'tv_channels.edit','display_name'=>'Edit TV Channel Record','division_name'=>'general','group_name'=>'tv_channels'],
    ['name'=>'tv_channels.delete','display_name'=>'Delete TV Channel Record','division_name'=>'general','group_name'=>'tv_channels'],
    ['name'=>'tv_channels.enable','display_name'=>'Toggle TV Channel Enable On/Off','division_name'=>'general','group_name'=>'tv_channels'],
    ['name'=>'tv_channels.change_order','display_name'=>'Change Order TV Channel','division_name'=>'general','group_name'=>'tv_channels'],

    //TV Channel Categories
    ['name'=>'tv_channel_categories.index','display_name'=>'TV Channel Category List','division_name'=>'general','group_name'=>'tv_channel_categories'],
    ['name'=>'tv_channel_categories.create','display_name'=>'Create TV Channel Category Record','division_name'=>'general','group_name'=>'tv_channel_categories'],
    ['name'=>'tv_channel_categories.view','display_name'=>'View TV Channel Category Record','division_name'=>'general','group_name'=>'tv_channel_categories'],
    ['name'=>'tv_channel_categories.edit','display_name'=>'Edit TV Channel Category Record','division_name'=>'general','group_name'=>'tv_channel_categories'],
    ['name'=>'tv_channel_categories.delete','display_name'=>'Delete TV Channel Category Record','division_name'=>'general','group_name'=>'tv_channel_categories'],
    ['name'=>'tv_channel_categories.change_order','display_name'=>'Change Order TV Channel Category','division_name'=>'general','group_name'=>'tv_channel_categories'],

    //Regular Messages
    ['name'=>'regular_messages.index','display_name'=>'Regular Message List','division_name'=>'general','group_name'=>'regular_messages'],
    ['name'=>'regular_messages.create','display_name'=>'Create Regular Message','division_name'=>'general','group_name'=>'regular_messages'],
    ['name'=>'regular_messages.edit','display_name'=>'Edit Regular Message','division_name'=>'general','group_name'=>'regular_messages'],
    ['name'=>'regular_messages.view','display_name'=>'View Regular Message','division_name'=>'general','group_name'=>'regular_messages'],
    ['name'=>'regular_messages.delete','display_name'=>'Delete Regular Message','division_name'=>'general','group_name'=>'regular_messages'],
    ['name'=>'regular_messages.delete_selected','display_name'=>'Delete Selected Regular Message(s)','division_name'=>'general','group_name'=>'regular_messages'],

    //Ticker
    ['name'=>'tickers.index','display_name'=>'Ticker Broadcast List','division_name'=>'general','group_name'=>'tickers'],
    ['name'=>'tickers.create','display_name'=>'Create Ticker Broadcast','division_name'=>'general','group_name'=>'tickers'],
    ['name'=>'tickers.resend','display_name'=>'Resend Ticker Broadcast','division_name'=>'general','group_name'=>'tickers'],
    ['name'=>'tickers.view','display_name'=>'View Ticker Broadcast','division_name'=>'general','group_name'=>'tickers'],
    ['name'=>'tickers.delete','display_name'=>'Delete Ticker Broadcast','division_name'=>'general','group_name'=>'tickers'],
    ['name'=>'tickers.delete_selected','display_name'=>'Delete Selected Ticker Broadcast(s)','division_name'=>'general','group_name'=>'tickers'],

    //Emergency
    ['name'=>'emergencies.index','display_name'=>'Emergency Broadcast List','division_name'=>'general','group_name'=>'emergencies'],
    ['name'=>'emergencies.create','display_name'=>'Create Emergency Broadcast','division_name'=>'general','group_name'=>'emergencies'],
    ['name'=>'emergencies.resend','display_name'=>'Resend Emergency Broadcast','division_name'=>'general','group_name'=>'emergencies'],
    ['name'=>'emergencies.view','display_name'=>'View Emergency Broadcast','division_name'=>'general','group_name'=>'emergencies'],
    ['name'=>'emergencies.delete','display_name'=>'Delete Emergency Broadcast','division_name'=>'general','group_name'=>'emergencies'],
    ['name'=>'emergencies.delete_selected','display_name'=>'Delete Selected Emergency Broadcast(s)','division_name'=>'general','group_name'=>'emergencies'],

    //Advertisement
    ['name'=>'advertisements.index','display_name'=>'Advertisement Broadcast List','division_name'=>'general','group_name'=>'advertisements'],
    ['name'=>'advertisements.create','display_name'=>'Create Advertisement Broadcast','division_name'=>'general','group_name'=>'advertisements'],
    ['name'=>'advertisements.resend','display_name'=>'Resend Advertisement Broadcast','division_name'=>'general','group_name'=>'advertisements'],
    ['name'=>'advertisements.view','display_name'=>'View Advertisement Broadcast','division_name'=>'general','group_name'=>'advertisements'],
    ['name'=>'advertisements.delete','display_name'=>'Delete Advertisement Broadcast','division_name'=>'general','group_name'=>'advertisements'],
    ['name'=>'advertisements.delete_selected','display_name'=>'Delete Selected Advertisement Broadcast(s)','division_name'=>'general','group_name'=>'advertisements'],

    //Video Ads
    ['name'=>'video_ads.index','display_name'=>'Video Ads List','division_name'=>'general','group_name'=>'video_ads'],
    ['name'=>'video_ads.create','display_name'=>'Create Video Ads','division_name'=>'general','group_name'=>'video_ads'],
    ['name'=>'video_ads.enable','display_name'=>'Enable Video Ads','division_name'=>'general','group_name'=>'video_ads'],
    ['name'=>'video_ads.edit','display_name'=>'Edit Video Ads','division_name'=>'general','group_name'=>'video_ads'],
    ['name'=>'video_ads.view','display_name'=>'View Video Ads','division_name'=>'general','group_name'=>'video_ads'],
    ['name'=>'video_ads.delete','display_name'=>'Delete Video Ads','division_name'=>'general','group_name'=>'video_ads'],
    ['name'=>'video_ads.delete_selected','display_name'=>'Delete Selected Video Ads','division_name'=>'general','group_name'=>'video_ads'],
    ['name'=>'video_ads.change_order','display_name'=>'Change Order Video Ads','division_name'=>'general','group_name'=>'video_ads'],


];
