@extends('layouts.main')
@section('title', $game->title)
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row no-gutters align-items-center justify-content-between poster" style="
                    background:url({{asset($game->poster)}})  center center;
                    ">
                </div>
                <div class="row">
                    <div class="card-body p-5">
                        <div class="row">
                            @include('includes.message')
                            <div class="col-lg-9">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h1 class="card-title">{{$game->title}}</h1>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{!! $game->info !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <img src="{{$game->logo?asset($game->logo):asset('img/no_photo.png')}}" class="card-img"
                                     alt="...">
                                @can('subscribe',[\App\manyToManyModels\GameSubscriber::class,$game])
                                    <form action="{{route('subscriber.store')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="game_id" value="{{$game->id}}">
                                        <button type="submit" class="btn btn-success w-100 mt-2">Subscribe to the game
                                        </button>
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
                        <div class="row mt-5">
                            <div class="col-lg-9">
                                <div class="card">
                                    <div class="card-header">
                                        <h3>News</h3>
                                    </div>
                                    <div class="card-body">
                                        @can('create',[App\oneHasManyModels\GamePosts::class,$game])
                                            <hr>
                                            <div class="card">
                                                <div class="card-header">
                                                    New post
                                                </div>
                                                <div class="card-body">
                                                    <form method="POST" action="{{ route('game.post.store') }}"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="game_id" value="{{$game->id}}">
                                                        <div class="form-group row">
                                                            <label for="title"
                                                                   class="col-md-2 col-form-label ">{{ __('Post title *') }}</label>
                                                            <div class="col-md-10">
                                                                <input id="title" type="text"
                                                                       class="form-control @error('title') is-invalid @enderror"
                                                                       name="title" value="{{ old('title') }}" required
                                                                       autocomplete="title" autofocus>
                                                                @error('title')
                                                                <span class="red" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="post"
                                                                   class="col-md-2 col-form-label ">{{ __('Game post *') }}</label>
                                                            <div class="col-md-10">
                                                                <textarea class="@error('post') is-invalid @enderror"
                                                                          name="post" required
                                                                          id="editor">{{ old('post') }}</textarea>
                                                                @error('post')
                                                                <span class="red" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="photo"
                                                                   class="col-md-2 col-form-label ">
                                                                {{ __('Upload photo') }}
                                                                <i class="fas fa-info-circle orange"
                                                                   title="The photo should be 640x360px otherwise it will be cropped to this resolution."></i>
                                                            </label>
                                                            <div class="col-md-10">
                                                                <input id="photo" type="file"
                                                                       class="form-control @error('photo') is-invalid @enderror"
                                                                       name="photo" autofocus>
                                                                @error('photo')
                                                                <span class="invalid-feedback" role="alert">
                                                                 <strong>{{ $message }}</strong>
                                                        </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-6 ">
                                                                <button type="submit" class="btn btn-success">
                                                                    {{ __('Create') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endcan

                                        @forelse($posts as $item)
                                            <div class="card my-5">
                                                <div class="card-header text-center">
                                                    @can('delete',[$item->post,$game])
                                                        <form action="{{route('game.post.destroy',['post'=>$item->post,'game'=>$game])}}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn mt-2 float-right red"><i class="fas fa-times"></i> Delete</button>
                                                        </form>
                                                    @endcan
                                                    @isset($item->post->photo)
                                                        <img src="{{asset($item->post->photo)}}" alt="" class="mb-5">
                                                    @endisset
                                                    <h3>{{$item->post->title}}</h3>
                                                </div>
                                                <div class="card-body">
                                                    <p>{!! $item->post->post !!}</p>
                                                    <small class="float-right">{{$item->post->created_at}}</small>
                                                </div>
                                            </div>
                                        @empty
                                            No posts yet
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <a class="card-header"
                                       href="{{route('allGameSubscribers',['game'=>$game->short_address])}}">
                                        <h4>Subscribers <span class="blocks">{{count($subscribers)}}</span></h4>
                                    </a>
                                    <div class="card-body">
                                        @forelse($subscribers as $subscriber)
                                            <div class="subscribe-item">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-4">
                                                        <div class="wall-post-img">
                                                            <img src="{{asset($subscriber->user->photo)}}" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <div class="wall-post-author">
                                                            <h6>
                                                                <a href="{{route('profile',['id'=>$subscriber->user->id])}}">{{$subscriber->user}}</a>
                                                                <br>
                                                                @if($subscriber->user->isOnline())
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
                                            No subscribers yet
                                        @endforelse
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
