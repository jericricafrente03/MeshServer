<style>
    #common-header-bell-notification:hover,
    #common-header-bell-notification:focus,
    #common-header-bell-notification:active {
        outline: none;
        box-shadow: none;
        color: #0D677C;
        background-color: #c2f4f5;
    }

    #common-header-bell-notification {
        padding-right: 16px;
        margin-right: -115%;
    }
</style>
<div class="top-menu ms-auto end">
    <ul class="navbar-nav align-items-center gap-1">

        <li class="nav-item dropdown dropdown-large">
            <a id="common-header-bell-notification"
                class="nav-link position-relative"
                href="javascript:;"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                    <!-- <span class="alert-count">7</span> 
                    <i class="fa fa-bell fa-sm ms-2"></i> -->
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="javascript:;">
                    <div class="msg-header">
                        <p class="msg-header-title">Notification</p>
                        {{-- <p class="msg-header-clear ms-auto">Marks all as read</p> --}}
                    </div>
                </a>
                <div class="header-message-list" id="common-header-bell-notificaion-list">
                    {{-- Notifications will be dynamically loaded here --}}
                    <a class="dropdown-item dashboard-alert-primary" href="/guest-billings?search=?744">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-primary text-primary"><i class="fa fa-cutlery"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name" data-bs-toggle="tooltip" title="Test">Testing Notification!
                                </h6>
                                <p class="msg-time">2025-04-08 11:59 AM</p>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item dashboard-alert-primary" href="javascript:;">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-primary text-primary"><i class="bx bx-mail-send"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name" data-bs-toggle="tooltip" title="Test">Testing Notification 2!
                                </h6>
                                <p class="msg-time">2025-04-08 12:01 PM</p>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item dashboard-alert-primary" href="javascript:;">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-primary text-primary"><i class="bx bx-mail-send"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name" data-bs-toggle="tooltip" title="Test">Testing Notification 3!
                                </h6>
                                <p class="msg-time">2025-04-08 12:01 AM</p>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item dashboard-alert-primary" href="javascript:;">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-primary text-primary"><i class="bx bx-mail-send"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name" data-bs-toggle="tooltip" title="Test">Testing Notification 4!
                                </h6>
                                <p class="msg-time">2025-04-08 12:01 PM</p>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item dashboard-alert-primary" href="javascript:;">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-primary text-primary"><i class="bx bx-mail-send"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name" data-bs-toggle="tooltip" title="Test">Testing Notification 5!
                                </h6>
                                <p class="msg-time">2025-04-08 12:01 AM</p>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item dashboard-alert-primary" href="javascript:;">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-primary text-primary"><i class="bx bx-mail-send"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name" data-bs-toggle="tooltip" title="Test">Testing Notification 6!
                                </h6>
                                <p class="msg-time">2025-04-08 12:01 PM</p>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item dashboard-alert-primary" href="javascript:;">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-primary text-primary"><i class="bx bx-mail-send"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name" data-bs-toggle="tooltip" title="Test">Testing Notification 7!
                                </h6>
                                <p class="msg-time">2025-04-08 12:02 AM</p>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item dashboard-alert-primary" href="javascript:;">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-primary text-primary"><i class="bx bx-mail-send"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name" data-bs-toggle="tooltip" title="Test">Testing Notification 8!
                                </h6>
                                <p class="msg-time">2025-04-08 12:02 PM</p>
                            </div>
                        </div>
                    </a>
                </div>
                <a href="{{ auth()->user()->can('guest_billings.index') ? '/guest-billings' : '/admin' }}" id="common-header-bell-view-all-announcement-list">
                    <div class="text-center msg-footer">View All</div>
                </a>
            </div>
        </li>

    </ul>
</div>