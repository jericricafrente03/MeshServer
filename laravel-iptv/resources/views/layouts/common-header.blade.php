<!--wrapper-->
	<div class="wrapper">
            @include('layouts/common-sidebar')

		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand gap-3">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="top-menu-left d-none d-lg-block">
				 	    <ul class="nav">
						
                        </ul>
                    </div>
					<div class="top-menu ms-auto end">
                    </div>
                    @include('layouts/notification')
                    <div class="top-menu">
                        <ul class="navbar-nav align-items-center gap-1">
                            
                            <li class="nav-item dropdown dropdown-large d-none">
                               
                                <div class="dropdown-menu dropdown-menu-end">
                                    
                                    <div class="header-notifications-list">
                                        
                                    </div>
                                    
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large d-none">
                               
                                <div class="dropdown-menu dropdown-menu-end">
                                    
                                    <div class="header-message-list">
                                            
                                    </div>
                                    
                                </div>
                            </li>
                        </ul>
                    </div>
                    
					<div class="user-box dropdown px-3">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            
                            @if(isset($user->id))
                                @if(!empty($user->image))
                                    <img src="/upload/userprofile/{{$user->image}}" class="user-img" alt="user avatar">
                                @else
                                    <div class="col-auto">
                                        <div class="avatar-{{$user->initials_random_color}}-initials" style="width: 42px !important; height: 42px !important; font-size: 15px !important;">
                                            {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="col-auto">
                                    <div class="user-avatar-initials">
                                        {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                                    </div>
                                </div>
                            @endif
							<div class="user-info ps-3">
                                @if(isset($user->id))
                                    <p class="user-name mb-0">{{ $user->firstname }} {{ $user->lastname }}</p>
                                    <span class="designattion mb-0">{{$user->role->display_name}}</span>
                                @else
								    <p class="user-name mb-0">{{ $user->name }}</p>
                                    <span class="designattion mb-0">{{$user->role->display_name}}</span>
								@endif
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="/admin/profile/profile_view"><i class="bx bx-user"></i><span>My Profile</span></a>
							</li>
							<!-- <li><a class="dropdown-item" href="javascript:;"><i class="bx bx-cog"></i><span>Settings</span></a>
							</li> -->
							
							</li>
							<li>
								<div class="dropdown-divider mb-0"></div>
							</li>
							
							<li><a class="dropdown-item" href="/admin/logout"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->
    