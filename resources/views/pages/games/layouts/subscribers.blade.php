@extends('layouts.main')
@section('title',$game->title.' subscribers')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3>
                <a href="{{route('game.show',['game'=>$game->short_address])}}" style="font-size: inherit">
                    {{$game->title}}
                </a>
                subscribers
            </h3>

            <ul class="nav">
                <li class="nav-item {{ (request()->routeIs('allGameSubscribers',['game'=>$game->short_address])) ? 'active' : '' }}">
                    <a class="nav-link " href="{{route('allGameSubscribers',['game'=>$game->short_address])}}">
                        All subscribers
                    </a>
                </li>
                <li class="nav-item {{ (request()->routeIs('onlineSubscribers',['game'=>$game->short_address])) ? 'active' : '' }}">
                    <a class="nav-link " href="{{route('onlineSubscribers',['game'=>$game->short_address])}}">
                        Online
                    </a>
                </li>
                <li class="nav-item {{ (request()->routeIs('gameSubscribersFriends',['game'=>$game->short_address])) ? 'active' : '' }}">
                    <a class="nav-link " href="{{route('gameSubscribersFriends',['game'=>$game->short_address])}}">
                        Friends
                    </a>
                </li>
            </ul>
            <form class="form-inline mt-4" action="{{url()->current()}}" method="GET">
                <input class="form-control mr-sm-2" type="search" name="q" placeholder="Search"
                       aria-label="Search" value="{{isset($_GET['q'])?$_GET['q']:''}}">
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
