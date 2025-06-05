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
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img  id="showImage" src="{{(!empty($profileData->image)) ? url('upload/userprofile/'.$profileData->image) : url('images/blank.png')}}" alt="profile" class="rounded-circle p-1 bg-primary" width="110">
                                <div class="mt-3">
                                    <button class="btn btn-outline-primary" id="uploadButton">Change Photo</button>
                                </div>
                            </div>

                            <form method="POST" action="{{url('/admin/profile/update_profile')}}" enctype="multipart/form-data" class="forms-sample">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <input type="file" name="image" class="form-control d-none" id="image">
                            </div>
                            
                            <h6 class="mb-0 text-uppercase">User Information</h6>
                            <hr/>
                            
                            <div class="col-lg-12">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" name="firstname" id="first_name" value="{{$profileData->firstname}}" class="mb-10 form-control">
                                        <span class="text-danger">{{ $errors->first('firstname') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" name="lastname" id="last_name" value="{{$profileData->lastname}}" class="mb-10 form-control">
                                        <span class="text-danger">{{ $errors->first('lastname') }}</span>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nickname" class="form-label">Nickname</label>
                                        <input type="text" name="nickname" id="nickname" value="{{$profileData->nickname}}" class="mb-10 form-control">
                                        <span class="text-danger">{{ $errors->first('nickname') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{$profileData->date_of_birth}}" class="mb-10 form-control">
                                        <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                    </div>
                                </div> 
                            </div>
                            
                            <h6 class="mb-0 text-uppercase" style="margin-top: 20px;">Contact Information</h6>
                            <hr/>
                            <div class="col-lg-12">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="contact_number" class="form-label">Phone Number</label>
                                        <input type="text" name="contact_number" id="contact_number" value="{{$profileData->contact_number}}" class="mb-10 form-control">
                                        <span class="text-danger">{{ $errors->first('contact_number') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" value="{{$profileData->email}}" class="mb-10 form-control">
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" name="address" id="address" value="{{$profileData->address}}" class="mb-10 form-control">
                                        <span class="text-danger">{{ $errors->first('address') }}</span>
                                    </div>
                                </div> 
                            </div>
                            
                            <br/>
                            <button type="submit" class="btn btn-primary px-4 float-end" onclick="return confirm('Submit Form?');">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
			
			</div>
		</div>
		<!--end page wrapper -->
        @include('sweetalert2/script')
@stop
@section('pages_specific_scripts') 
<script type="text/javascript">

    var formData = new FormData();

    $(document).ready(function(){
        $('#image').on('change', function(e){
            formData = new FormData();
            if(this.files.length){
                var image = e.target.files[0];
                
                formData.append('image', image);
                saveAvatar();

                $('#showImage').attr( 'src', URL.createObjectURL(e.target.files[0]));
            }else{
                $('#showImage').attr( 'src','/images/blank.png');
            }
        });
    });

    $("#uploadButton").click(function(){
        $("#image").click();
    });

    function saveAvatar() {
        // console.log("form",formData)
        let data = {
            id: {{$profileData->id}}
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
