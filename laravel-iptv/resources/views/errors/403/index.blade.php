@extends('layouts.master')
@section('content')
<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			<!-- PAGE-HEADER -->

            {{-- <div class="error-404 d-flex align-items-center justify-content-center">
                <div class="container"> --}}
                    <div class="card py-5">
                        <div class="row g-0">
                            <div class="col col-xl-5 col-md-5">
                                <div class="card-body p-4">
                                    <h1 class="display-1"><span class="text-danger">403</span></h1>
                                    <h2 class="font-weight-bold display-4">Forbidden</h2>
                                    <p>You have reached the edge of the universe.
                                        <br>The page you requested was forbidden.
                                        <br>Dont'worry and return to the home page.</p>
                                        <div class="mt-5">
                                            <a href="/lost" class="btn btn-danger btn-lg px-md-5 radius-30">Go Home</a>
                                        </div>
                                </div>
                            </div>
                            <div class="col-xl-7 col-md-7">
                                <img src="/source-images/errors-images/403.jpg" class="img-fluid" alt="">
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                {{-- </div>
            </div> --}}
		</div>
		<!--end page wrapper -->
@stop
