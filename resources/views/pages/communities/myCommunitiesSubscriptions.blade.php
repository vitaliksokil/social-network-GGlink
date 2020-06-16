@extends('layouts.main')
@section('title',isset($user) ? strip_tags($user).'\'s communities
 subscriptions' : 'My Communities')
@section('content')
    <div class="row justify-content-sm-center">
        <div class="col-lg-10 col-sm-10 col-xxl-12">
            <div class="card">
                <div class="card-header pt-0">
                    @isset($user)
                        <h3>
                            <a href="{{route('profile',['id'=>$user->id])}}">
                                {{$user}}'s communities subscriptions
                            </a>
                        </h3>
                    @else
                        <div class="actions-panel mb-3">
                            <ul class="nav d-flex justify-content-between">
                                <li class="{{ (request()->routeIs('community.my')) ? 'active' : '' }}">
                                    <a class="nav-link " href="{{route('community.my')}}">
                                        <i class="fas fa-cog grey"></i>
                                        Managed communities
                                    </a>
                                </li>
                                <li class="{{ (request()->routeIs('community.create')) ? 'active' : '' }}">
                                    <a class="nav-link " href="{{route('community.create')}}" >
                                        <i class="fas fa-plus green"></i>
                                        Add new community
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <hr>

                        <a href="{{route('community.my.subscriptions')}}"><h3>My community subscriptions </h3></a>

                    @endisset
                    <form class="form-inline mt-4" action="{{url()->current()}}" method="GET">
                        <input class="form-control mr-sm-2" type="search" name="q" placeholder="Search"
                               aria-label="Search" value="{{isset($_GET['q'])?$_GET['q']:''}}">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                                class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @include('includes.message')
                            @forelse($communities as $item)
                                <div class="subscribe-item">
                                    <div class="row align-items-center">
                                        <div class="col-lg-2 col-sm-2">
                                            <div class="wall-post-img">
                                                <a href="{{route('community.show',['community'=>$item->community->short_address])}}">
                                                    <img src="{{asset($item->community->logo)}}" alt="">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-sm-6">
                                            <div class="wall-post-author">
                                                <h6>
                                                    <a href="{{route('community.show',['community'=>$item->community->short_address])}}">
                                                        <h1>{{$item->community->title}}</h1>
                                                    </a>

                                                    <a href="{{route('allCommunitySubscribers',['community'=>$item->community->short_address])}}">
                                                        Subscribers:<span class="blocks"> {{count($item->community->subscribers)}}</span>
                                                    </a>
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-4">
                                            @can('isCreator',[App\CommunitySubscriber::class,$item->community])
                                                <form action="{{route('community.destroy',['community'=>$item->community])}}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger w-100 mt-2">
                                                        <i class="fas fa-trash-alt"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                            @can('isAdmin',[App\CommunitySubscriber::class,$item->community])
                                                <a href="{{route('community.edit',['community'=>$item->community])}}" class="btn btn-primary w-100">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </a>
                                            @endcan
                                            @cannot('isCreator',[App\CommunitySubscriber::class,$item->community])
                                                @can('subscribe',[App\CommunitySubscriber::class,$item->community])
                                                    <form action="{{route('community.sub.store')}}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="community_id" value="{{$item->community->id}}">
                                                        <button type="submit" class="btn btn-success w-100 mt-2">
                                                            Subscribe
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{route('community.sub.destroy',['community'=>$item->community])}}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger w-100 mt-2">Unsubscribed</button>
                                                    </form>
                                                @endcan
                                            @endcannot
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <h4>No communities yet</h4>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
