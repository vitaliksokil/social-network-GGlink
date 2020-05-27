@extends('layouts.main')
@section('title','All users')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3>All users</h3>
            <form class="form-inline my-2 my-lg-0 w-100" action="{{route('users.all')}}" method="GET">
                <input class="form-control mr-sm-2" style="width: 30%;" type="search" placeholder="Search by id,name,nickname or surname"
                       aria-label="Search" name="q" value="{{isset($_GET['q']) ? $_GET['q'] : ''}}">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                        class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            @forelse($allUsers as $user)
                <div class="subscribe-item">
                    <div class="row align-items-center">
                        <div class="col-lg-1 ">
                            <div class="wall-post-img">
                                <a href="{{route('profile',['id'=>$user->id])}}">
                                    <img src="{{asset($user->photo)}}" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-11">
                            <div class="wall-post-author">
                                <h6>
                                    <a href="{{route('profile',['id'=>$user->id])}}">{{$user}}</a> <br>
                                    @if($user->isOnline())
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
                No users
            @endforelse
                {{ $allUsers->links() }}

        </div>
    </div>
@endsection
