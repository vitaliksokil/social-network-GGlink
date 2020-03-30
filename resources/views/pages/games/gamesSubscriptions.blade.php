@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-header">
            @isset($user)
                <h3>{{$user->id != Auth::user()->id ? $user.'\'s ':''}} favourite games</h3>
            @else
                <h3>My games subscriptions</h3>
            @endisset
            <form class="form-inline mt-4">
                <input class="form-control mr-sm-2" type="search" placeholder="Search"
                       aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                        class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @forelse($games as $game)
                        <div class="subscribe-item">
                            <div class="row align-items-center">
                                <div class="col-lg-1 ">
                                    <div class="wall-post-img">
                                        <a href="#">

                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-11">
                                    <div class="wall-post-author">
                                        <h6>
                                            <a href="#">

                                            </a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <h4>No games yet</h4>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
