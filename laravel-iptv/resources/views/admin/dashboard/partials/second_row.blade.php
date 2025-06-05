@php
    $hasDevicesPermission = auth()->user()->can('analytics.devices');
    $hasGuestPermission = auth()->user()->can('analytics.room_assignments');
    $columnClass = ($hasDevicesPermission && $hasGuestPermission) ? 'col-lg-3' : 'col-lg-6';
@endphp

<div class="row">
    @if($hasDevicesPermission)
        <div class="{{ $columnClass }} col-6">
            <!-- small box -->
            <div class="small-box bg-db-online-stb">
            <div class="inner">
                <h3 id="onlineStbCount">...</h3>

                <p>Online STB</p>
            </div>
            <div class="icon">
                <i class="fa fa-solid fa-hard-drive"></i>
            </div>
            <a href="/device_monitor" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="{{ $columnClass }} col-6">
            <!-- small box -->
            <div class="small-box bg-db-offline-stb">
            <div class="inner">
                <h3 id="offlineStbCount">...</h3>

                <p>Offline STB</p>
            </div>
            <div class="icon">
            <i class="fa fa-regular fa-hard-drive"></i>
            </div>
            <a href="/device_monitor" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    @endif

    @if($hasGuestPermission)
        <div class="{{ $columnClass }} col-6">
            <!-- small box -->
            <div class="small-box bg-db-guest-checkin">
            <div class="inner">
                <h3 id="guestCheckinCount">...</h3>

                <p>Guest Checkin</p>
            </div>
            <div class="icon">
                <i class="fa fa-solid fa-bed"></i>
            </div>
            <a href="/room-assignments" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="{{ $columnClass }} col-6">
            <!-- small box -->
            <div class="small-box bg-db-guest-checkout">
            <div class="inner">
                <h3 id="guestCheckoutCount">...</h3>

                <p>Guest Checkout</p>
            </div>
            <div class="icon">
                <i class="fa fa-solid fa-person-walking-luggage"></i>
            </div>
            <a href="/room-assignments" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    @endif
</div>