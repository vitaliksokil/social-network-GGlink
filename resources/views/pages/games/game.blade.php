@extends('layouts.main')
@section('title', $game->title)
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row no-gutters align-items-center justify-content-between poster" style="
                    background:url({{asset($game->poster)}})  center center;
                    ">
                    <div class="col-md-9">
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div class="row">
                    <div class="card-body p-5">
                        <div class="row">
                            <div class="col-lg-10">
                                <h1 class="card-title">{{$game->title}}</h1>
                                <hr>
                                <p class="card-text">{!! $game->info !!}</p>
                            </div>
                            <div class="col-lg-2">
                                <img src="{{$game->logo?asset($game->logo):asset('img/no_photo.png')}}" class="card-img"
                                     alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
