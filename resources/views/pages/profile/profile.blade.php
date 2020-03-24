@extends('layouts.main')
@section('title', Auth::user())
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3 p-3">
                <div class="row no-gutters">
                    <div class="col-md-2">
                        <img src="{{$user->photo?asset($user->photo):asset('img/no_photo.png')}}" class="card-img"
                             alt="...">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <h1 class="card-title">{{$user}}</h1>
                            <p class="card-text">{{$user->about}}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card-body">
                            <h5 class="card-title">Favourites games</h5>
                            <div class="favourite-games">
                                <div class="game-img">
                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                </div>
                                <div class="game-img">
                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                </div>
                                <div class="game-img">
                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                </div>
                                <div class="game-img">
                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                </div>
                                <div class="game-img">
                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                </div>
                                <div class="game-img">
                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                </div>
                                <div class="game-img">
                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                </div>
                                <div class="game-img">
                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                </div>
                                <div class="game-img">
                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <span class="green"><i class="fas fa-circle"></i> Online</span> <!-- todo ONLINE/OFFLINE-->
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Communities <span class="blocks">20</span></h3>
                                </div>
                                <div class="card-body">
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Community name</a> <br>
                                                        <p class="header">members <span class="blocks">20</span></p>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Community name</a> <br>
                                                        <p class="header">members <span class="blocks">20</span></p>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Community name</a> <br>
                                                        <p class="header">members <span class="blocks">20</span></p>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Community name</a> <br>
                                                        <p class="header">members <span class="blocks">20</span></p>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Community name</a> <br>
                                                        <p class="header">members <span class="blocks">20</span></p>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row mt-5">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Friends <span class="blocks">20</span></h3>
                                </div>
                                <div class="card-body">
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Friend name</a> <br>
                                                        <span class="green"><i class="fas fa-circle"></i> Online</span> <!-- todo ONLINE/OFFLINE-->
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Friend name</a> <br>
                                                        <span class="green"><i class="fas fa-circle"></i> Online</span> <!-- todo ONLINE/OFFLINE-->
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Friend name</a> <br>
                                                        <span class="green"><i class="fas fa-circle"></i> Online</span> <!-- todo ONLINE/OFFLINE-->
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Friend name</a> <br>
                                                        <span class="green"><i class="fas fa-circle"></i> Online</span> <!-- todo ONLINE/OFFLINE-->
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subscribe-item">
                                        <div class="row align-items-center">

                                            <div class="col-lg-2 ">
                                                <div class="wall-post-img">
                                                    <img src="{{asset('img/dota2.jpg')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <div class="wall-post-author">
                                                    <h6><a href="#">Friend name</a> <br>
                                                        <span class="green"><i class="fas fa-circle"></i> Online</span> <!-- todo ONLINE/OFFLINE-->
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-8">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            <div class="wall">
                <div class="card">
                    <div class="card-header">
                        New post
                    </div>
                    <div class="card-body">
                        <form class="text-right" action="{{route('post.store')}}" method="POST">
                            @csrf
                            <textarea class="form-control" name="post" id="" ></textarea>
                            <button type="submit" class="btn profile-btn mt-2">Post</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="wall mt-3">
                <div class="card">
                    <div class="card-header">
                        Wall
                    </div>
                    <div class="card-body">

                            @forelse ($posts as $post)
                            <div class="card mb-3 p-3">
                                <div class="wall-post">
                                    <div class="row align-items-center">
                                        <div class="col-lg-1">
                                            <div class="wall-post-img">
                                                <img src="{{asset($post->writer->photo)}}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-11">
                                            <div class="wall-post-author">
                                                @if($user->id == Auth::user()->id || $post->writer->id == Auth::user()->id)
                                                    <i class="fas fa-times blocks" style="cursor: pointer;float: right" onclick="event.preventDefault(); document.getElementById('delete-post').submit();">
                                                        Delete post
                                                        <form id="delete-post" action="{{ route('post.destroy',['post'=>$post]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </i>
                                                @endif
                                                <h6>
                                                    <a href="{{route('profile',['id'=>$post->writer->id])}}">{{$post->writer}}</a> <br><small class="header">{{$post->created_at}}</small>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            {{$post->post}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                                <p>No posts yet</p>
                            @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
