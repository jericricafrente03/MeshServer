 @include('layouts/common-head')
@include('layouts/common-header')
@yield('content')

{{-- @include('layouts/common-chatbox-popup') --}}
@include('components/modal/view-announcement')
@include('layouts/common-footer')
