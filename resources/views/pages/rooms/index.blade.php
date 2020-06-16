@extends('layouts.main')
@section('title','Rooms')
@section('content')
    <div class="row justify-content-sm-center">
        <div class="col-lg-10 col-sm-10 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h3>Rooms</h3>
                    <form class="form-inline mt-4" action="{{url()->current()}}" method="GET">
                        <input class="form-control mr-sm-2" type="search" name="q" placeholder="Search"
                               aria-label="Search" value="{{isset($_GET['q'])?$_GET['q']:''}}">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                                class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($games as $game)
                            <div class="col-lg-3 col-sm-3 mb-3  d-flex">
                                <div class="card">
                                    <a href="{{route('rooms.of.game',['game_short_address'=>$game->short_address])}}">
                                        <img src="/{{$game->logo}}" class="card-img-top" alt="game logo">
                                    </a>
                                    <div class="card-body d-flex align-items-center justify-content-center flex-column">
                                        <a href="{{route('rooms.of.game',['game_short_address'=>$game->short_address])}}">
                                            <h5 class="card-title text-center ">{{$game->title}}</h5>
                                        </a>
                                        <small class="card-text">
                                            Rooms: <span class="pink">{{count($game->rooms->where('is_locked',0))}}</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h3 class="text-center">No games found</h3>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
