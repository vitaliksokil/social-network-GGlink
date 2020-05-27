@extends('pages.friends.layouts.friends')
@section('friends_content')
    @forelse($requestedPeople as $requestedPerson)
        <div class="subscribe-item">
            <div class="row align-items-center">
                <div class="col-lg-1 ">
                    <div class="wall-post-img">
                        <a href="{{route('profile',['id'=>$requestedPerson->receiver->id])}}">
                            <img src="{{asset($requestedPerson->receiver->photo)}}" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-11">
                    <div class="wall-post-author">
                        <h6><a href="{{route('profile',['id' => $requestedPerson->receiver->id])}}">{{$requestedPerson->receiver}}</a>
                            <br>
                            <span class="green"><i class="fas fa-circle"></i> Online</span>
                            <form action="{{route('deleteFriend',['id' => $requestedPerson->receiver->id])}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger">Cancel request</button>
                            </form>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <h4>
            No friendship requests have been found
        </h4>
    @endforelse

@endsection
