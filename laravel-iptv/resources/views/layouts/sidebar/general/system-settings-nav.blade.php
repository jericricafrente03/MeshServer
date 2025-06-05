@canany(array_merge(array_keys($menuSettingsGroupPermissions, 'user'),array_keys($menuSettingsGroupPermissions, 'role'),array_keys($menuSettingsGroupPermissions, 'rbac'),array_keys($menuSettingsGroupPermissions, 'log')))
<!-- <li class="menu-label">System Settings</li> -->

<!-- System Users -->
<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><i class='bx bx-user' ></i></div>
        <div class="menu-title">System Users</div>
    </a>
    <ul>
        @canany(array_keys($menuSettingsGroupPermissions, 'user'))
            <li>
                <a href="/admin/user"><i class="bx bx-right-arrow-alt"></i>Users</a>
            </li>
        @endcanany
        @canany(array_keys($menuSettingsGroupPermissions, 'role'))
            <li>
                <a href="/admin/role"><i class="bx bx-right-arrow-alt"></i>Roles</a>
            </li>
        @endcanany
        @canany(array_keys($menuSettingsGroupPermissions, 'rbac'))
            <li>
                <a href="/admin/rbac"><i class="bx bx-right-arrow-alt"></i>RBAC</a>
            </li>
        @endcanany
        @canany(array_keys($menuSettingsGroupPermissions, 'log'))
            <li>
                <a href="/admin/log"><i class="bx bx-right-arrow-alt"></i>Log</a>
            </li>
        @endcanany
    </ul>
</li>
@endcanany

@canany(array_merge(
        array_keys($menuSettingsGroupPermissions, 'system_config'),
        array_keys($menuSettingsGroupPermissions, 'default_apps'),
        array_keys($menuSettingsGroupPermissions, 'zones'),
        array_keys($menuSettingsGroupPermissions, 'themes'),
    ))
<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><i class="fa-solid fa-gears fa-xs"></i></div>
        <div class="menu-title">System Settings</div>
    </a>
    <ul>
        @canany(['system_config.index'])
            <li>
                <a href="/admin/system_config"><i class="bx bx-right-arrow-alt"></i>System Config</a>
            </li>
        @endcanany
        @canany(
                array_merge(
                    array_keys($menuSettingsGroupPermissions, 'default_apps'),
                    array_keys($menuSettingsGroupPermissions, 'zones'),
                    array_keys($menuSettingsGroupPermissions, 'themes'),
                )
            )
                <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Theme Manager</a>
                    <ul>
                        @canany(['default_apps.index'])
                            <li>
                                <a href="/default-apps"><i class="bx bx-right-arrow-alt"></i>Default Apps</a>
                            </li>
                        @endcanany
                        @canany(['zones.index'])
                            <li>
                                <a href="/zones"><i class="bx bx-right-arrow-alt"></i>Zones</a>
                            </li>
                        @endcanany
                        @canany(['themes.index'])
                            <li>
                                <a href="/themes"><i class="bx bx-right-arrow-alt"></i>Themes</a>
                            </li>
                        @endcanany
                    </ul>
                </li>
        @endcanany
    </ul>
</li>
@endcanany

@canany(array_merge(
        array_keys($menuSettingsGroupPermissions, 'device_adb_manager'),
        array_keys($menuSettingsGroupPermissions, 'device_adb_screen_capture'),
    ))
<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><i class="fa-brands fa-android fa-xs"></i></div>
        <div class="menu-title">Device ADB</div>
    </a>
    <ul>
        @canany(['device_adb_manager.index'])
            <li>
                <a href="/adb-manager"><i class="bx bx-right-arrow-alt"></i>Manager</a>
            </li>
        @endcanany
        @canany(
                array_merge(
                    array_keys($menuSettingsGroupPermissions, 'device_adb_screen_capture'),
                )
            )
                <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Files</a>
                    <ul>
                        @canany(['device_adb_screen_capture.index'])
                            <li>
                                <a href="/adb-screen-capture"><i class="bx bx-right-arrow-alt"></i>Screen Capture</a>
                            </li>
                        @endcanany
                        @canany(['device_adb_screen_record.index'])
                            <li>
                                <a href="/adb-screen-record"><i class="bx bx-right-arrow-alt"></i>Screen Record</a>
                            </li>
                        @endcanany
                    </ul>
                </li>
        @endcanany
        @canany(['device_adb_apk.index'])
            <li>
                <a href="/adb-apk"><i class="bx bx-right-arrow-alt"></i>APK</a>
            </li>
        @endcanany
    </ul>
</li>
@endcanany
<!--  -->
