@extends('layouts.main')
@section('title', $user)
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3 p-3">
                <div class="row no-gutters">
                    <div class="col-md-2">
                        <img src="{{$user->photo?asset($user->photo):asset('img/no_photo.png')}}" class="card-img"
                             alt="...">
                        @if($authUser->id == $user->id)
                            <a href="{{route('edit')}}" class="btn btn-grey w-100">Edit</a>
                            <!-- checking if that user send offer to u -->
                        @elseif(array_search($user->id,array_column($authUser->new_friends->toArray(),'sender_id')) !== false)
                            <form action="{{route('friendAccept',['sender_id'=>$user->id,'receiver_id'=>$authUser->id])}}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-pink w-100">Accept friendship</button>
                            </form>
                        @elseif($isSentRequest)
                            <a class="btn btn-info w-100 disabled">Request was sent</a>
                            <form action="{{route('deleteFriend',['id' => $user->id])}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">Cancel request</button>
                            </form>
                        @elseif($isFriend)
                            <a class="btn btn-grey w-100 disabled">You are friends</a>
                            <form action="{{route('deleteFriend',['id' => $user->id])}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">Delete from friends</button>
                            </form>
                        @else
                            <form action="{{route('addFriend')}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mt-2">Add friend</button>
                            </form>
                        @endif
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <h1 class="card-title">{{$user}}</h1>
                            @if($user->show_email)
                                <p class="card-text">Email: {{$user->email}}</p>
                            @endif
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
                    @if($user->isOnline())
                        <h2 class="green"><i class="fas fa-circle"></i> Online</h2>
                    @else
                        <h2 class="blocks"><i class="fas fa-circle"></i> Offline</h2>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="card">
                                <a class="card-header" href="{{$user->id == Auth::user()->id ? route('friendsAll') : route('friendsById',['id'=>$user->id])}}">
                                    <h3>Friends <span class="blocks">{{count($friends)}}</span></h3>
                                </a>
                                <div class="card-body">
                                    @forelse($friends as $friend)
                                        <div class="subscribe-item">
                                            <div class="row align-items-center">

                                                <div class="col-lg-2 ">
                                                    <div class="wall-post-img">
                                                        <img src="{{asset($friend->photo)}}" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-10">
                                                    <div class="wall-post-author">
                                                        <h6><a href="{{route('profile',['id'=>$friend->id])}}">{{$friend}}</a> <br>
                                                            @if($friend->isOnline())
                                                                <span class="green"><i class="fas fa-circle"></i> Online</span>
                                                            @else
                                                                <span class="blocks"><i class="fas fa-circle"></i> Offline</span>
                                                            @endif
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        No friends yet
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row mt-5">
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
                        Wall
                        @cannot('postToWall',[App\Post::class,$user])
                            <i class="fas fa-lock"></i>
                        @endcannot
                    </div>
                    <div class="card-body">
                        @can('postToWall',[App\Post::class,$user])
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
                        @endcan
                            @forelse ($posts as $post)
                            <div class="card mt-3 p-3">
                                <div class="wall-post">
                                    <div class="row align-items-center">
                                        <div class="col-lg-1">
                                            <div class="wall-post-img">
                                                <img src="{{asset($post->writer->photo)}}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-11">
                                            <div class="wall-post-author">
                                                @if($user->id == $authUser->id || $post->writer->id == $authUser->id)
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
