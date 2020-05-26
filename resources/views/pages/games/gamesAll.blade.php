@extends('layouts.main')
@section('title','All Games')
@section('content')
    <div class="card">
        <div class="card-header pt-0">
            <div class="actions-panel mb-3">
                <ul class="nav d-flex justify-content-between">
                    @can('create',App\Game::class)
                        <li class="nav-item {{ (request()->routeIs('game.create')) ? 'active' : '' }}">
                            <a class="nav-link " href="{{route('game.create')}}">
                                <i class="fas fa-plus green"></i>
                                Add new game
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
            <a href="{{route('gamesAll')}}">
                <h3>
                    Games
                </h3>
            </a>

            <form class="form-inline my-2 my-lg-0 w-100">
                <input class="form-control mr-sm-2" type="search" placeholder="Search"
                       aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                        class="fas fa-search"></i></button>
            </form>

            @if (session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

        </div>
        @hasSection('form')
            <div class="card-body">
                @yield('form')
            </div>
        @else
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @forelse($games as $game)
                            <div class="subscribe-item">
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="wall-post-img">
                                            <a href="{{route('game.show',['game'=>$game->short_address])}}">
                                                <img src="{{asset($game->logo)}}" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="wall-post-author">
                                            <h6>
                                                <a href="{{route('game.show',['game'=>$game->short_address])}}">
                                                    <h1>{{$game->title}}</h1>
                                                </a>
                                                <a href="{{route('allGameSubscribers',['game'=>$game->short_address])}}">
                                                    Subscribers:<span class="blocks"> {{count($game->subscribers)}}</span>
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        @can('update',$game)
                                            <a class="nav-link float-right blue"
                                               href="{{route('game.edit',['game'=>$game])}}">
                                                <i class="fas fa-pen"></i> Edit
                                            </a>
                                        @endcan
                                        @can('delete',$game)
                                            <a class="nav-link float-right gameDelete red"
                                               data-delete-id="{{$game->id}}">
                                                <i class="fas fa-times"></i> Delete
                                                <form id="delete-game-{{$game->id}}"
                                                      action="{{ route('game.destroy',['game'=>$game]) }}" method="POST"
                                                      style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </a>
                                        @endcan
                                            @can('subscribe',[\App\manyToManyModels\GameSubscriber::class,$game])
                                                <form action="{{route('subscriber.store')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="game_id" value="{{$game->id}}">
                                                    <button type="submit" class="btn btn-success w-100 mt-2">Subscribe to the game</button>
                                                </form>
                                            @else
                                                <form action="{{route('subscriber.destroy',['game'=>$game])}}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger w-100 mt-2">Unsubscribed</button>
                                                </form>
                                            @endcan
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h4>No games yet</h4>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
