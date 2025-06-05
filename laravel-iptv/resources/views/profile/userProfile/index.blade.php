@extends('layouts.master')
@section('content')
<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Profile</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">User Profile</li>
							</ol>
						</nav>
					</div>
				</div>
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="btn btn-primary" data-bs-toggle="pill" href="#" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="fa-regular fa-user"></i>&nbsp;
                                </div>
                                <div class="tab-title">Account</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="btn btn-default"  href="/admin/user/security" tabindex="-1">
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
                        <div class="card-body">
                            <div>
                                <a class="d-flex align-items-center">
                                    <input type="file" name="profile_avatar_file" hidden/>
                                    <img name="profile_avatar_img" class="rounded-circle p-1 bg-light" width="110" src="{{(!empty($user->image)) ? url('upload/userprofile/'.$user->image) : url('images/blank.png')}}" alt="profile" style="cursor: pointer;">

                                    <div class="user-info ps-3">
                                        <h6>{{ $user->firstname}} {{$user->lastname}}</h6>
                                        <p class="designattion mb-0">{{$user->name}}</p>
                                        <p class="designattion mb-0">{{$user->role->name}}</p>
                                    </div>
                                </a>
                            </div>
                            <h6 class="mb-0 text-uppercase" style="margin-top: 40px;">User Information</h6>
                            <hr/>
                            <div class="col-lg-12">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <h6 class="mb-10 text-uppercase">{{$user->firstname}}</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <h6 class="mb-10">{{$user->lastname}}</h6>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nickname" class="form-label">Username</label>
                                        <h6 class="mb-10">{{$user->name}}</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Role</label>
                                        <h6 class="mb-10">{{$user->role->name}}</h6>
                                    </div>
                                </div> 
                            </div>
                            
                            <!-- <h6 class="mb-0 text-uppercase" style="margin-top: 40px;">Contact Information</h6>
                            <hr/>
                            <div class="col-lg-12">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Phone Number</label>
                                        <h6 class="mb-10"></h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Personal Email</label>
                                        <h6 class="mb-10"></h6>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Address</label>
                                        <h6 class="mb-10"></h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Work Email</label>
                                        <h6 class="mb-10"></h6>
                                    </div>
                                </div> 
                            </div> -->
                        
                        </div>
                    </div>
                </div>
			
			</div>
		</div>
		<!--end page wrapper -->
        @include('sweetalert2/script')
@stop
@section('pages_specific_scripts')   
<script>

    var formData = new FormData();
    var imgBtn = document.querySelector('[name="profile_avatar_img"]');
    var fileInp = document.querySelector('[name="profile_avatar_file"]');

    imgBtn.addEventListener('click', function() {
        formData = new FormData();
        fileInp.click();
    })

    $('input[type=file]').change(function () {
        if(this.files.length){
            var image = event.target.files[0];   
            formData.append('image', image);
            saveProfileAvatar();
            $('[name="profile_avatar_img"]').attr( 'src', URL.createObjectURL(event.target.files[0]));
        }else{
            $('[name="profile_avatar_img"]').attr( 'src','/images/blank.png');
        }
    });

    function saveProfileAvatar() {
        // console.log("form",formData)
        let data = {
            id: {{$user->id}}
        };
        formData.append('data', JSON.stringify( data ));
        $.ajax({
            //laravel requires this thing, it fetches it from the meta up in the head
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            url: "/admin/profile/update_avatar",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(data) {
                sweetAlert2(data.status, "Profile Avatar has been udpated");
            },error: function(msg) {
                //general error
                console.log("Error");
                console.log(msg);
                $.each(msg.responseJSON.errors,function (key , val){
                    sweetAlert2('warning', 'Check field inputs.');
                });
            }
        });
    }
</script>
@stop
