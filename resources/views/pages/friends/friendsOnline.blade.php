@extends('pages.friends.layouts.friends')
@section('friends_content')
    @forelse($onlineFriends as $friend)
        <div class="subscribe-item">
            <div class="row align-items-center">
                <div class="col-lg-1 col-sm-3">
                    <div class="wall-post-img">
                        <a href="{{route('profile',['id'=>$friend->id])}}">
                            <img src="{{asset($friend->photo)}}" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-11 col-sm-9">
                    <div class="wall-post-author">
                        <h6>
                            <a href="{{route('profile',['id'=>$friend->id])}}">{{$friend}}</a> <br>
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
        <h4>No online friends</h4>
    @endforelse
@endsection
