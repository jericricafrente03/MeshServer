@extends('layouts.master')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
        <!-- PAGE-HEADER -->
        @include('layouts/pageContentHeader/index')
        <!-- PAGE-HEADER END -->
        @php
            $name = isset($systemConfig) ? $systemConfig->name : '';
            $description = isset($systemConfig) ? $systemConfig->description : '';
            $street = isset($systemConfig) ? $systemConfig->street : '';
            $city = isset($systemConfig) ? $systemConfig->city : '';
            $country_code = isset($systemConfig) ? $systemConfig->country_code : '';
            $time_zone = isset($systemConfig) ? $systemConfig->time_zone : '';
            $email = isset($systemConfig) ? $systemConfig->email : '';
            $currency = isset($systemConfig) ? $systemConfig->currency : '';
            $website = isset($systemConfig) ? $systemConfig->website : '';
            $logo = isset($systemConfig) ? ($systemConfig->logo == '') ? '/images/blank.png' : '/'.$systemConfig->logo : '/images/blank.png';
            $lat = isset($systemConfig) ? $systemConfig->lat : '';
            $lon = isset($systemConfig) ? $systemConfig->lon : '';
            $welcome_message = isset($systemConfig) ? $systemConfig->welcome_message : '';
            $max_idle = isset($systemConfig) ? $systemConfig->max_idle : '';
        @endphp
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST" action="{{url('/system_config/update')}}" enctype="multipart/form-data" class="forms">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">System Name:</label>
                                    <input type="text" name="name" class="form-control"  placeholder="System Name" value="{{$name}}" autocomplete="off">
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Description:</label>
                                    <textarea name="description" class="form-control" placeholder="Description" >{{$description}}</textarea>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-label">Welcome Message:</label>
                                    <textarea name="welcome_message" class="form-control" placeholder="Welcome Message">{{$welcome_message}}</textarea>
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label class="form-label" for="img">Logo:</label>
                                </div>

                                <div class="form-group col-md-12 text-center">
                                    <p><a href="{{$logo}}" target="_blank"><img src="{{$logo}}" class="img-thumbnail" width="250" height="250" id="img-thumbnail"></a></p>
                                </div> 

                                <div class="form-group col-md-12">
                                    <input type="file" name="logo" id="logo" onchange="if(this.files[0]!=undefined){document.getElementById('img-thumbnail').src = window.URL.createObjectURL(this.files[0])}else{}">
                                    <small>Maximum allowed content: 5MB (jpg, png)</small>
                                    <span class="text-danger"><br>{{ $errors->first('logo') }}</span>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label">Street:</label>
                                    <input type="text" name="street" class="form-control" value="{{$street}}" placeholder="Street" autocomplete="off">
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label">City:</label>
                                    <input type="text" name="city" class="form-control" value="{{$city}}" placeholder="City" autocomplete="off">
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label">Latitude:</label>
                                    <input type="text" name="lat" class="form-control" value="{{$lat}}" placeholder="Latitude" autocomplete="off">
                                    <span class="text-danger">{{ $errors->first('lat') }}</span>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label">Longitude:</label>
                                    <input type="text" name="lon" class="form-control" value="{{$lon}}" placeholder="Longitude" autocomplete="off">
                                    <span class="text-danger">{{ $errors->first('lon') }}</span>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-label">Country:</label>
                                    <select name="country_code" class="form-control" id="country" data-placeholder="Select Country...">
                                        <option></option>
                                        @foreach($countries as $country)
                                            @if($country_code == $country->country_code)
                                                <option selected value="{{ $country->country_code }}">{{ $country->country_name }}</option>
                                            @endif
                                            <option value="{{ $country->country_code }}">{{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="form-label">Time Zone:</label>
                                    <select name="timezone" class="form-control" id="timezone" data-placeholder="Select Time Zone...">
                                        <option></option>
                                        @foreach($timeZones as $timezone)
                                            @if($time_zone == $timezone->id)
                                                <option selected value="{{ $timezone->id }}">{{ $timezone->zone_name }}</option>
                                            @endif
                                            <option value="{{ $timezone->id }}">{{ $timezone->zone_name }}</option>
                                        @endforeach
                                    </select>
                                    </select>
                                </div>


                                <div class="form-group col-md-6">
                                    <label class="form-label">Email:</label>
                                    <input type="email" name="email" class="form-control" value="{{$email}}" placeholder="Email" autocomplete="off">
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label">Website:</label>
                                    <input type="text" name="website" class="form-control" value="{{$website}}" placeholder="Website" autocomplete="off">
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label">Currency:</label>
                                    <input type="text" name="currency" class="form-control" value="{{$currency}}" placeholder="Currency" autocomplete="off">
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label">Idle Timeout: </label>(per minute)
                                    <input type="text" name="max_idle" class="form-control" value="{{$max_idle}}" placeholder="Idle Timeout" autocomplete="off">
                                    <span class="text-danger">{{ $errors->first('max_idle') }}</span>
                                </div>

                                <div class="form-group col-md-12">
                                    <div class="text-end">
                                        <input type="submit" class="btn btn-success" name="btn_submit" value="Submit" onClick="return confirm('Are you sure you want to save this record ?');">
                                    </div>
                                </div>
                           
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
        <div class="position-fixed top-0 end-0 p-3 mt-5" style="z-index: 11" id="alert-message-for-systemconfig-updates"></div>                     

        @stop

@section('pages_specific_scripts')
<script>
    $(document).ready(function() {    
        $('textarea').summernote({
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],    
                //['fontname', ['fontname']],
                //['fontsize', ['fontsize']],
                //['color', ['color']],
                ['para', ['ul']],//, 'ol', 'paragraph']],
                //['table', ['table']],
                //['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview']]//, 'help']]
            ]
        });

        
        select2Dropdown('#country')
        select2Dropdown('#timezone')

    });

    function select2Dropdown(__selector){
        $(__selector).select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: true,
        });
    }

    

</script>

@if(session('status'))
    <script>
        $(document).ready(function() {
            const color = 'success'; // or define based on the message
            const text = '{{ session('status') }}'; // The status message
            const data = {
                data: {
                    id: 'unique-id', // You can change this based on your context
                    display_name: 'Display Name' // Replace with actual data
                },
                permission: 'Permission Name' // Replace with actual permission name
            };

            $('#alert-message-for-systemconfig-updates').append(`
                <div id="alert-${data.data.id}" class="alert alert-${color} alert-dismissible shadow fade show py-2 mb-2 me-2" style="max-width: 500px;">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-gray"><i class='bx bxs-check-circle'></i></div>
                        <div class="ms-3">
                            <div><b>${text}</b></div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            
            setTimeout(function() {
                $(`#alert-${data.data.id}`).alert('close');
            }, 2500);
        });
    </script>
@endif
@stop