@auth
    <div class="container-fluid">
        <div class="row pt-4">
            <nav class="col-sm-2 col-lg-2 d-none d-sm-block sidebar card ml-5  ml-sm-0 p-sm-0  " style="z-index: 999">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item {{ (request()->fullUrlIs(route('profile',['id'=>Auth::user()->id]))) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('profile',['id'=>Auth::user()->id])}}">
                                <span class="col-lg-1">
                                    <i class="fas fa-home"></i>
                                </span>
                                <span class="col-lg-11">
                                    My profile
                                </span>
                            </a>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('friendsAll')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('friendsAll')}}">
                                <span class="col-lg-1">
                                    <i class="fas fa-user"></i>
                                </span>
                                <span class="col-lg-11">
                                    Friends
                                @if(count(Auth::user()->new_friends))
                                        <span class="blocks float-right">+{{count(Auth::user()->new_friends)}}</span>
                                    @endif
                                </span>
                            </a>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('messages.page')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('messages.page')}}">
                                <span class="col-lg-1">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <span class="col-lg-11">
                                    Messages
                                    <span class="blocks float-right" id="messagesCount">
                                        @if(count(Auth::user()->newMessages()))
                                            +{{count(Auth::user()->newMessages())}}
                                        @endif
                                        </span>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('gamesSubscriptions')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('gamesSubscriptions')}}">
                                <span class="col-lg-1">
                                    <img src="{{asset('img/icons/my-games.svg')}}" class="icon" alt="">
                                </span>
                                <span class="col-lg-11">
                                    Games
                                </span>
                            </a>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('community.my.subscriptions')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('community.my.subscriptions')}}">
                                <span class="col-lg-1">
                                    <i class="fas fa-user-friends"></i>
                                </span>
                                <span class="col-lg-11">
                                    Communities
                                </span>
                            </a>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('rooms.index')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('rooms.index')}}">
                                <span class="col-lg-1">
                                   <i class="fas fa-dungeon"></i>
                                </span>
                                <span class="col-lg-11">
                                    Rooms
                                </span>
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <hr>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item {{ (request()->routeIs('users.all')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('users.all')}}">
                                <span class="col-lg-1">
                                    <img src="{{asset('img/icons/all-users.svg')}}" class="icon" alt="">
                                </span>
                                <span class="col-lg-11">
                                    All users
                                </span>
                            </a>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('gamesAll')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('gamesAll')}}">
                                <span class="col-lg-1">
                                    <i class="fas fa-gamepad"></i>
                                </span>
                                <span class="col-lg-11">
                                    All games
                                </span>
                            </a>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('community.all')) ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('community.all')}}">
                                <span class="col-lg-1">
                                    <i class="fas fa-users"></i>
                                </span>
                                <span class="col-lg-11">
                                    All communities
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container container-sm  main-wrap">
                <main role="main" class="main col-md-12 col-lg-12  px-4 ml-sm-3 ml-md-4 ml-lg-5 ml-xxl-0">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
@else
    <main class="main container container-sm py-4">
        @yield('content')
    </main>
@endauth
