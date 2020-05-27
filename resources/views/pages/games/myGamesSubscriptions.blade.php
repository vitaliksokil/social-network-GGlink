@extends('layouts.main')
@section('title',isset($user) ? strip_tags($user).'\'s games subscriptions' : 'My Games' )
@section('content')
    <div class="card">
        <div class="card-header">
            @isset($user)
                <h3>
                    <a href="{{route('profile',['id'=>$user->id])}}">
                        {{$user}}'s favourite games
                    </a>
                </h3>
            @else
                <h3>My games subscriptions</h3>
            @endisset
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
                    @include('includes.message')
                    @forelse($games as $game)
                        <div class="subscribe-item">
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <div class="wall-post-img">
                                        <a href="{{route('game.show',['game'=>$game->game->short_address])}}">
                                            <img src="{{asset($game->game->logo)}}" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="wall-post-author">
                                        <h6>
                                            <a href="{{route('game.show',['game'=>$game->game->short_address])}}">
                                                <h1>{{$game->game->title}}</h1>
                                            </a>

                                            <a href="{{route('allGameSubscribers',['game'=>$game->game->short_address])}}">
                                                Subscribers:<span class="blocks"> {{count($game->game->subscribers)}}</span>
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <form action="{{route('subscriber.destroy',['game'=>$game->game])}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100 mt-2">Unsubscribed</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <h4>No games found</h4>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
