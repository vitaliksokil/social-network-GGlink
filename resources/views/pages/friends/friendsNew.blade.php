@extends('pages.friends.layouts.friends')
@section('friends_content')
    @forelse($newFriends as $newFriend)
        <div class="subscribe-item">
            <div class="row align-items-center">
                <div class="col-lg-1 ">
                    <div class="wall-post-img">
                        <a href="{{route('profile',['id'=>$newFriend->sender->id])}}">
                            <img src="{{asset($newFriend->sender->photo)}}" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-11">
                    <div class="wall-post-author">
                        <h6><a href="{{route('profile',['id' => $newFriend->sender->id])}}">{{$newFriend->sender}}</a>
                            <br>
                            <span class="green"><i class="fas fa-circle"></i> Online</span>
                            <form
                                action="{{route('friendAccept',['sender_id'=>$newFriend->sender->id,'receiver_id'=>Auth::user()->id])}}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-pink">Accept friendship</button>
                            </form>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <h4>No new friends found</h4>
    @endforelse

@endsection
