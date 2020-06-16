@extends('pages.games.layouts.subscribers')
@section('subscribers_content')
    @include('includes.message')
    <div class="card">
        <div class="card-header">
            @if(request()->routeIs('onlineSubscribers',['game'=>$game->short_address]))
                <h3>Number of online subscribers: <span class="blocks">{{count($subscribers)}}</span></h3>
            @elseif(request()->routeIs('gameSubscribersFriends',['game'=>$game->short_address]))
                <h3>Number of friends: <span class="blocks">{{count($subscribers)}}</span></h3>
            @else
                <h3>Number of subscriptions: <span class="blocks">{{count($subscribers)}}</span></h3>
            @endif
        </div>
    </div>
    @forelse($subscribers as $subscriber)
        <div class="subscribe-item">
            <div class="row align-items-center">
                <div class="col-lg-1 col-sm-2">
                    <div class="wall-post-img">
                        <a href="{{route('profile',['id'=>$subscriber->user->id])}}">
                            <img src="{{asset($subscriber->user->photo)}}" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-9 col-sm-7">
                    <div class="wall-post-author">
                        <h6>
                            <a href="{{route('profile',['id'=>$subscriber->user->id])}}">{{$subscriber->user}}</a> <br>
                            @if($subscriber->user->isOnline())
                                <span class="green"><i class="fas fa-circle"></i> Online</span>
                                @else
                                <span class="blocks"><i class="fas fa-circle"></i> Offline</span>
                                @endif
                        </h6>
                    </div>
                </div>
                @can('isSuperAdmin')
                    <div class="col-lg-2 col-sm-2">
                        @if($subscriber->is_moderator)
                            <button class="btn btn-success" disabled>Moderator</button>
                            <form action="{{route('game.removeModerator',['gameSubscriber'=>$subscriber])}}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-pink">Remove moderator</button>
                            </form>
                        @else
                            <form action="{{route('game.addModerator',['gameSubscriber'=>$subscriber])}}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-primary">Make the user a moderator</button>
                            </form>
                        @endif
                    </div>
                @endcan
            </div>
        </div>
    @empty
        <h4>No subscribers found</h4>
    @endforelse
@endsection
