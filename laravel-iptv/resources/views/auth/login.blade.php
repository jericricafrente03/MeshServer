<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="/source-images/images/meshtv_logo.png" sizes="32x32" />
	<link rel="icon" href="/source-images/images/meshtv_logo.png" sizes="192x192" />
	<link rel="apple-touch-icon" href="/source-images/images/meshtv_logo.png" />
	<!--plugins-->
	<link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="/assets/css/pace.min.css" rel="stylesheet" />
	<script src="/assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="/assets/css/app.css" rel="stylesheet">
	<link href="/assets/css/icons.css" rel="stylesheet">
	<link href="/css/custom.css" rel="stylesheet" />


	<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

	<title>MeshTV | IPTV</title>
	<style>
		/* login */

		body {
			background-image: url("/source-images/images/whitespace.jpg");
			background-size: 100% 100%;
		}
		.card-body{
			/* width: 100px; */
			min-height: 450px;
		}

		.login-sub-title{
			font-size: 170%;
			margin-left: -10%;
			margin-bottom: -5%;
			color: #000000;
		}

		.login-div-logo{
			height: 150px;
		}

		.login-main-text-color{
			color: #5A566B;
		}

        .btn-custom-login:hover {
            background-color: #ba1c24;
            border-color: #ba1c24;
            color: white;
        }
        .btn-custom-login {
            background-color: #4d4d4d;
            border-color: #4d4d4d;
            color: white;
        }

		.toast-container {
			position: fixed;
			top: 20px;
			/* right: 20px; */
			left: 50%;
            transform: translateX(-50%);
			z-index: 1050;
		}
		
		/* CSS for Mobile devices */
		@media (max-width: 480px) {
		/* Insert your CSS rules for mobile devices here */
		}

		/* CSS for Tablet devices */
		@media (min-width: 481px) and (max-width: 1024px) {
		/* Insert your CSS rules for tablet devices here */
		/* login */
			body {
				background-image: url("/source-images/images/whitespace.jpg");
				background-size: 100% 100%;
			}

			.card-body {
				/* width: 100px; */
				min-height: 450px;
			}

			.login-sub-title {
				font-size: 170%;
				margin-left: -10%;
				margin-bottom: -5%;
				color: #000000;
			}

			.login-div-logo {
				height: 100px;
				margin-left: 20%;
				display: block;
				/* margin-left: auto;
				margin-right: auto; */
				width: 50%;
			}

			.login-main-text-color {
				color: #5a566b;
			}
		}

	</style>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!-- <div class="authentication-header"></div> -->
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						
						<div class="card rounded-4">
							<div class="card-body">
								<div class="p-4 rounded">
									<div class="text-center">
										<img src="/source-images/images/meshtv_logo.png" width="180" class="img-fluid mb-3" alt="" />
										<h3 class="login-main-text-color" style="font-weight: 700;">MeshTV IPTV</h3>
										<p class="login-main-text-color">Please sign-in your account</a>
										</p>
									</div>
									
									<div class="form-body">
										
                                         <form class="row g-3 login100-form validate-form login-main-text-color" method="POST" action="{{ route('login') }}">
                            				@csrf
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email</label>
												<input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your Email">
												@error('email')
													<div class="text-danger">{{ $message }}</div>
												@enderror
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label col-7">Password</label>
										
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control" id="password" name="password" placeholder="••••••••••••••••••••"> 
												</div>
												@error('password')
													<div class="text-danger">{{ $message }}</div>
												@enderror
											</div>
											
											
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-custom-login">Sign in</button>
												</div>
											</div>
										</form>
										
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="/assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="/assets/js/jquery.min.js"></script>
	<!-- Toast notification for login errors -->
	@if ($errors->has('login'))
	<div class="toast-container">
		<!-- <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
			<div class="toast-header">
				<strong class="mr-auto text-danger">Login Error</strong>
			</div> -->
		<div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="d-flex">
				<div class="toast-body">
					{{ $errors->first('login') }}
				</div>
				<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
	</div>
	
	<script>
		$(document).ready(function(){
			$('.toast').toast('show');
		});
	</script>
	@endif
</body>

</html>
