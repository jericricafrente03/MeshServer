@extends('layouts.master')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!-- PAGE-HEADER -->
        @include('layouts/pageContentHeader/index')
        <!-- PAGE-HEADER END -->
        
        <ul class="nav nav-pills mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="btn btn-default" href="/admin/profile/profile_view" aria-selected="true">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="fa-regular fa-user"></i>&nbsp;
                        </div>
                        <div class="tab-title">Account</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="btn btn-primary" href="#" role="tab" aria-selected="false" tabindex="-1">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="fa-solid fa-lock"></i>&nbsp;
                        </div>
                        <div class="tab-title">Security</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{url('/admin/user/update_password')}}" enctype="multipart/form-data" class="forms-sample">
                            @csrf
                            @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" value="" name="current_password" class="form-control" id="current_password" placeholder="••••••••••">
                            <span class="text-danger">{{ $errors->first('current_password') }}</span>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" value="" name="password" class="form-control" id="password" placeholder="••••••••••">
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Password Confirmation</label>
                            <input type="password" value="" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="••••••••••">
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        </div>
                        
                    </div>
                    <br/>
                    <h6>Password Requirements:</h6>
                    <ul>
                        <li>Password should be between 8 and 20 characters.</li>
                        <li>At least 1 uppercase letter, and lowercase letter.</li>
                        <li>At least 1 number, and special character.</li>
                    </ul>  
                    <div class="row g-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" id="save_btn" onclick="">Save Changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('sweetalert2/script')
</div>
<!--end page wrapper -->
@stop
@section('pages_specific_scripts')
<script>

 $(document).ready(function(){
        
        @if(session('success'))
            sweetAlert2('success', 'Record has been saved.');
        @endif

});
</script>
@stop
