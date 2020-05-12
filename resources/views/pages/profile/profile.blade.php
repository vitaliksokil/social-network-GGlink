@extends('layouts.main')
@section('title', strip_tags($user))
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-3">
                <div class="row no-gutters">
                    <div class="col-md-2">
                        <img src="{{asset($user->photo)}}" class="card-img"
                             alt="...">
                        @if($authUser->id == $user->id)
                            <a href="{{route('edit')}}" class="btn btn-grey w-100">Edit</a>
                            <!-- checking if that user send offer to u -->
                        @elseif(array_search($user->id,array_column($authUser->new_friends->toArray(),'sender_id')) !== false)
                            <form
                                action="{{route('friendAccept',['sender_id'=>$user->id,'receiver_id'=>$authUser->id])}}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-pink w-100">Accept friendship</button>
                            </form>
                        @elseif($isSentRequest)
                            <a class="btn btn-info w-100 disabled">Request was sent</a>
                            <form action="{{route('deleteFriend',['id' => $user->id])}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">Cancel request <i class="fas fa-times"></i></button>
                            </form>
                        @elseif($isFriend)
                            <a class="btn btn-grey w-100 disabled">You are friends</a>
                            <form action="{{route('deleteFriend',['id' => $user->id])}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">Delete from friends <i class="fas fa-user-minus"></i></button>
                            </form>
                        @else
                            <form action="{{route('addFriend')}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mt-2">Add friend</button>
                            </form>
                        @endif
                        @can('sendTo',[App\Message::class,$user])
                            @can('isConversationExist',[Auth::user()->id,$user->id])
                                <a href="{{route('conversation',[$user->nickname,$user->id])}}" class="btn btn-primary w-100 mt-2">
                                    Send a message <i class="fas fa-envelope"></i>
                                </a>
                            @else
                                @if(Auth::user()->id != $user->id)
                                    <a class="btn btn-primary w-100 mt-2" data-toggle="modal" data-target="#firstMessage">
                                        Send a message <i class="fas fa-envelope"></i>
                                    </a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="firstMessage" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-center">
                                                        Write your first message
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('send.message')}}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="to" value="{{$user->id}}">
                                                        <div class="form-group row">
                                                            <label for="text"
                                                                   class="col-md-4 col-form-label text-md-right">{{ __('Your message') }}</label>
                                                            <div class="col-md-6">
                                                        <textarea name="text" id="text" class="form-control @error('text') is-invalid @enderror"
                                                                  required autofocus
                                                        ></textarea>
                                                                @error('text')
                                                                <span class="invalid-feedback" role="alert">
                                                     <strong>{{ $message }}</strong>
                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <button type="submit" class="btn btn-success">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endcan

                        @endcan
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                            <h1 class="card-title">{{$user}}</h1>
                            @if($user->show_email)
                                <p class="card-text">Email: {{$user->email}}</p>
                                <hr>
                            @endif
                            <p class="card-text">{!! $user->about !!}</p>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{route('gamesSubscriptionsById',['id'=>$user->id])}}">
                                    Games subscriptions
                                </a>
                            </h5>
                            <div class="favourite-games">
                                @forelse($gamesSubscriptions as $game)
                                    <div class="game-img">
                                        <a href="{{route('game.show',['game'=>$game->game->short_address])}}">
                                            <img src="{{asset($game->game->logo)}}" alt="">
                                        </a>
                                    </div>
                                @empty
                                    No game subscriptions
                                @endforelse


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
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
                                <a class="card-header"
                                   href="{{$user->id == Auth::user()->id ? route('friendsAll') : route('friendsById',['id'=>$user->id])}}">
                                    <h3>Friends <span class="blocks">{{count($friends)}}</span></h3>
                                </a>
                                <div class="card-body">
                                    @forelse($friends as $friend)
                                        <div class="subscribe-item">
                                            <div class="row align-items-center">

                                                <div class="col-lg-4">
                                                    <div class="wall-post-img">
                                                        <img src="{{asset($friend->photo)}}" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="wall-post-author">
                                                        <h6>
                                                            <a href="{{route('profile',['id'=>$friend->id])}}">{{$friend}}</a>
                                                            <br>
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
                                    <a href="{{route('communitiesSubscriptionsById',['id'=>$user->id])}}">
                                        <h3>
                                            Communities
                                            <span class="blocks">{{count($communities)}}</span>
                                        </h3>
                                    </a>
                                </div>
                                <div class="card-body">
                                    @forelse($communities as $item)
                                        <div class="subscribe-item">
                                            <div class="row align-items-center">
                                                <div class="col-lg-3 ">
                                                    <div class="wall-post-img">
                                                        <a href="{{route('community.show',['community'=>$item->community->short_address])}}">
                                                            <img src="{{asset($item->community->logo)}}" alt="">
                                                        </a>

                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <div class="wall-post-author">
                                                        <h6>
                                                            <a href="{{route('community.show',['community'=>$item->community->short_address])}}">
                                                                {{$item->community->title}}
                                                            </a>
                                                            <br>
                                                            <p class="header">subscribers
                                                                <span class="blocks">{{count($item->community->subscribers)}}</span>
                                                            </p>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        No communities subscriptions yet
                                        @endforelse
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
                        @cannot('postToWall',[App\ProfileComment::class,$user])
                            <i class="fas fa-lock"></i>
                        @endcannot
                    </div>
                    <div class="card-body">
                        @can('postToWall',[App\ProfileComment::class,$user])
                            <div class="wall">
                                <div class="card">
                                    <div class="card-header">
                                        New comment
                                    </div>
                                    <div class="card-body">
                                        <form class="text-right" action="{{route('comment.store')}}" method="POST">
                                            @csrf
                                            <textarea class="form-control" name="comment" id=""></textarea>
                                            <button type="submit" class="btn profile-btn mt-2">Post</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @forelse ($comments as $comment)
                            <div class="card mt-3 p-3">
                                <div class="wall-post">
                                    <div class="row align-items-center">
                                        <div class="col-lg-1">
                                            <div class="wall-post-img">
                                                <img src="{{asset($comment->writer->photo)}}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-11">
                                            <div class="wall-post-author">
                                                @can('delete',$comment)
                                                    <i class="fas fa-times blocks postDelete"
                                                       data-delete-id="{{$comment->id}}"
                                                       style="cursor: pointer;float: right">
                                                        Delete post
                                                        <form id="delete-post-{{$comment->id}}"
                                                              action="{{ route('comment.destroy',['comment'=>$comment]) }}"
                                                              method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </i>
                                                @endcan
                                                <h6>
                                                    <a href="{{route('profile',['id'=>$comment->writer->id])}}">{{$comment->writer}}</a>
                                                    <br><small class="header">{{$comment->created_at}}</small>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            {{$comment->comment}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>No comments yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
