@extends('layouts.main')
@section('title',$community->title.' subscribers')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3>
                <a href="{{route('community.show',['community'=>$community->short_address])}}" style="font-size: inherit">
                    {{$community->title}}
                </a>
                subscribers
            </h3>

            <ul class="nav">
                <li class="nav-item {{ (request()->routeIs('allCommunitySubscribers',['community'=>$community->short_address])) ? 'active' : '' }}">
                    <a class="nav-link " href="{{route('allCommunitySubscribers',['community'=>$community->short_address])}}">
                        All subscribers
                    </a>
                </li>
                <li class="nav-item {{ (request()->routeIs('communityOnlineSubscribers',['community'=>$community->short_address])) ? 'active' : '' }}">
                    <a class="nav-link " href="{{route('communityOnlineSubscribers',['community'=>$community->short_address])}}">
                        Online
                    </a>
                </li>
                <li class="nav-item {{ (request()->routeIs('communitySubscribersFriends',['community'=>$community->short_address])) ? 'active' : '' }}">
                    <a class="nav-link " href="{{route('communitySubscribersFriends',['community'=>$community->short_address])}}">
                        Friends
                    </a>
                </li>
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
                    @yield('subscribers_content')
                </div>
            </div>
        </div>
    </div>

@endsection
