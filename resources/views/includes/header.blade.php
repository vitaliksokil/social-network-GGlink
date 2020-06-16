<header>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg justify-content-between">
                    <div class="col-lg-2 text-sm-center">
                        <a class="logo" href="{{url('/')}}">GG <span>link</span></a>
                    </div>
                    <div class="col-lg-10">
                        @guest
                            <ul class="navbar-nav align-items-center justify-content-end">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                            </ul>
                        @endif
                        @else
                            <ul class="navbar-nav align-items-center justify-content-end">
                                <li class="nav-item dropdown profile-li">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="name green">
                                            {{ Auth::user() }}
                                        </span>
                                        <span class="img">
                                            <img
                                                src="{{Auth::user()->photo ? asset(Auth::user()->photo) : asset('img/no_photo.png')}}"
                                                alt="profile img">
                                        </span>
                                    </a>
                                    <div class="dropdown-menu card-modal" aria-labelledby="navbarDropdown">
                                        <ul class="nav flex-column">
                                            <li class="nav-item {{ (request()->routeIs('edit')) ? 'active' : '' }}">
                                                <a class="nav-link" href="{{route('edit')}}">
                                                    <span class="col-lg-1">
                                                        <i class="fas fa-edit mr-3"></i>
                                                    </span>
                                                    <span class="col-lg-11">
                                                        Edit
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (request()->routeIs('settings')) ? 'active' : '' }}">
                                                <a class="nav-link" href="{{route('settings')}}">
                                                    <span class="col-lg-1">
                                                        <i class="fas fa-cog mr-3"></i>
                                                    </span>
                                                    <span class="col-lg-11">
                                                        Settings
                                                    </span>
                                                </a>
                                            </li>
                                            <div class="dropdown-divider"></div>
                                            <li class="nav-item">
                                                <a class="nav-link" id="logout">
                                                    <span class="col-lg-1">
                                                        <i class="fas fa-sign-out-alt red mr-3"></i>
                                                    </span>
                                                    <span class="col-lg-11 ">
                                                        Logout
                                                    </span>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                          style="display: none;">
                                                        {{ csrf_field() }}
                                                    </form>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        @endguest

                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
