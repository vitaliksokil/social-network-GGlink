@extends('layouts.main')
@section('title',$user->id != Auth::user()->id ? strip_tags($user).'\'s friends':'My Friends')
@section('content')
    <div class="row justify-content-sm-center">
        <div class="col-sm-10 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    @if($user->id != Auth::user()->id)
                        <h3>
                            <a href="{{route('profile',['id'=>$user->id])}}">
                                {{$user}}'s friends
                            </a>
                        </h3>
                    @else
                        <h3>Friends</h3>
                    @endif

                    <ul class="nav">
                        @if($user->id == Auth::user()->id)
                            <li class="nav-item {{ (request()->is('friends/all*')) ? 'active' : '' }}">
                                <a class="nav-link " href="{{route('friendsAll')}}">
                                    All
                                    <span class="blocks">{{count(Auth::user()->friends())}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{ (request()->is('friends/online*')) ? 'active' : '' }}">
                                <a class="nav-link" href="{{route('friendsOnline')}}">
                                    Online
                                    <span class="blocks">{{count(Auth::user()->onlineFriends())}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{ (request()->fullUrlIs(route('friendsNew'))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{route('friendsNew')}}">
                                    New
                                    @if($newFriendsCount = count(Auth::user()->new_friends))
                                        <span class="blocks">+{{$newFriendsCount}}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item {{ (request()->fullUrlIs(route('friendsRequestSent'))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{route('friendsRequestSent')}}">
                                    Requested
                                    @if($requestedPeopleCount = count(Auth::user()->requested_people))
                                        <span class="blocks">{{$requestedPeopleCount}}</span>
                                    @endif
                                </a>
                            </li>
                        @else
                            <li class="nav-item {{ (request()->fullUrlIs(route('friendsById',['id'=>$user->id]))) ? 'active' : '' }}">
                                <a class="nav-link " href="{{route('friendsById',['id'=>$user->id])}}">
                                    All
                                    <span class="blocks">{{count($user->friends())}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{ (request()->fullUrlIs(route('friendsOnlineById',['id'=>$user->id]))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{route('friendsOnlineById',['id'=>$user->id])}}">Online
                                    <span class="blocks">{{count($user->onlineFriends())}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{ (request()->fullUrlIs(route('mutualFriends',['id'=>$user->id]))) ? 'active' : '' }}">
                                <a class="nav-link" href="{{route('mutualFriends',['id' => $user->id])}}">
                                    Mutual friends
                                    <span class="blocks">{{count($user->mutualFriends())}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <form class="form-inline my-2 my-lg-0 w-100" action="{{url()->current()}}" method="GET">
                        <input class="form-control mr-sm-2" style="width: 30%;" type="search"
                               placeholder="Search by id,name,nickname or surname"
                               aria-label="Search" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                                class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @yield('friends_content')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
