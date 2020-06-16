@extends('pages.communities.layouts.subscribers')
@section('subscribers_content')
    @include('includes.message')
    <div class="card">
        <div class="card-header">
            @if(request()->routeIs('communityOnlineSubscribers',['community'=>$community->short_address]))
                <h3>Number of online subscribers: <span class="blocks">{{count($subscribers)}}</span></h3>
            @elseif(request()->routeIs('communitySubscribersFriends',['community'=>$community->short_address]))
                <h3>Number of friends: <span class="blocks">{{count($subscribers)}}</span></h3>
            @else
                <h3>Number of subscriptions: <span class="blocks">{{count($subscribers)}}</span></h3>
            @endif
        </div>
    </div>
    @forelse($subscribers as $item)
        <div class="subscribe-item">
            <div class="row align-items-center">
                <div class="col-lg-1 col-sm-2">
                    <div class="wall-post-img">
                        <a href="{{route('profile',['id'=>$item->user->id])}}">
                            <img src="{{asset($item->user->photo)}}" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 col-sm-5">
                    <div class="wall-post-author">
                        <h6>
                            <a href="{{route('profile',['id'=>$item->user->id])}}">{{$item->user}}</a> <br>
                            @if($item->user->isOnline())
                                <span class="green"><i class="fas fa-circle"></i> Online</span>
                            @else
                                <span class="blocks"><i class="fas fa-circle"></i> Offline</span>
                            @endif
                        </h6>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4">
                    @if($item->is_creator)
                        <h5 class="green">CREATOR</h5>
                    @elseif($item->is_admin)
                        <h5 class="green">ADMIN</h5>
                    @elseif($item->is_moderator)
                        <h5 class="green">MODERATOR</h5>
                    @endif
                </div>
                @if(!$item->is_creator)
                    @can('isCreator',[App\CommunitySubscriber::class,$community])
                        <div class="col-lg-2 col-sm-4">
                            @if($item->is_admin)
                                <button class="btn btn-success" disabled>ADMIN</button>
                                <form action="{{route('community.removeAdmin',['communitySubscriber'=>$item])}}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-pink">Remove admin</button>
                                </form>
                            @else
                                <form action="{{route('community.addAdmin',['communitySubscriber'=>$item])}}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-primary">Make the user an admin</button>
                                </form>
                            @endif
                        </div>
                    @endcan
                    @can('isAdmin',[App\CommunitySubscriber::class,$community])
                        <div class="col-lg-2 col-sm-4">
                            @if(!$item->is_admin)
                                @if($item->is_moderator)
                                    <button class="btn btn-success" disabled>Moderator</button>
                                    <form action="{{route('community.removeModerator',['communitySubscriber'=>$item])}}"
                                          method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-pink">Remove moderator</button>
                                    </form>
                                @else
                                    <form action="{{route('community.addModerator',['communitySubscriber'=>$item])}}"
                                          method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-primary">Make the user a moderator</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    @endcan
                @endif
            </div>
        </div>
    @empty
        <h4>No subscribers found</h4>
    @endforelse
@endsection
