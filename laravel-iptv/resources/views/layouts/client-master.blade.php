 @include('layouts/client-head')
			 <div id="layoutSidenav">
			 		 <div id="layoutSidenav_nav">
							<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
								<div class="sb-sidenav-menu">
									<div class="nav">
										<a class="nav-link" href="/admin/prescriptions">
											<div class="sb-nav-link-icon"><i class="fas fa-book" ></i></div>
											Prescriptions
										</a>
										<a class="nav-link" href="/admin/patients">
											<div class="sb-nav-link-icon"><i class="fas fa-users" ></i></div>
										Patients
										</a>

										<a class="nav-link" href="/admin/files">
											<div class="sb-nav-link-icon"><i class="fas fa-file" ></i></div>
											Files
										</a>	
									</div>
								</div>
								<div class="sb-sidenav-footer">
									<div class="small">Logged in: {{ $user->name }}</div>
									
								</div>
							</nav>
						</div>
					@yield('content')
			</div>
 @include('layouts/client-footer')
