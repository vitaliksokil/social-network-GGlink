<header>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg justify-content-between">
                    <div class="col-lg-2">
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
                            <ul class="navbar-nav align-items-center justify-content-between">
                                <li>
                                    <form class="form-inline my-2 my-lg-0">
                                        <input class="form-control mr-sm-2" type="search" placeholder="Search"
                                               aria-label="Search">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                                                class="fas fa-search"></i></button>
                                    </form>
                                </li>
                                <li class="nav-item dropdown profile-li">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         <span class="name green">
                                            {{ Auth::user() }}
                                         </span>
                                        <span class="img">
                                            <img src="{{Auth::user()->photo ? asset(Auth::user()->photo) : asset('img/no_photo.png')}}" alt="profile img">
                                        </span>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item justify-content-start" href="{{route('edit')}}"><i class="fas fa-edit mr-3"></i>Edit</a>
                                        <a class="dropdown-item justify-content-start" href="{{route('settings')}}"><i class="fas fa-cog mr-3"></i>Settings</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item justify-content-start" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt red mr-3"></i> Logout
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </a>
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
