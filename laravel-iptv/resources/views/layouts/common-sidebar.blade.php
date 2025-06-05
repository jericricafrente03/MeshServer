<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <!-- <div>
            <img src="/source-images/images/meshtv_logo.png" class="logo-icon" alt="logo icon">
        </div> -->
        <div>
            <img src="/source-images/images/meshtv_logo.png"  style="width: 50%; margin-top: 5px; margin-left: 20%; min-height: 15px; ">
        </div>
        <!-- <div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i>
        </div> -->
    </div>
    <!--navigation-->
    
    <ul class="metismenu" id="menu">
        
        @include('layouts/sidebar/general/dashboard-nav') 
        @include('layouts/sidebar/general/body-nav')
        @include('layouts/sidebar/general/system-settings-nav')
        {{-- @include('layouts/sidebar/general/other-nav') --}}

    </ul>
    

    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
