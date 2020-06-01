@extends('layouts.main')
@section('title','All Communities')
@section('content')
    <div class="card">
        <div class="card-header pt-0">
            <div class="actions-panel mb-3">
                <ul class="nav d-flex justify-content-between">
                    <li class="nav-item {{ (request()->routeIs('community.create')) ? 'active' : '' }}">
                        <a class="nav-link " href="{{route('community.create')}}">
                            <i class="fas fa-plus green"></i>
                            Add new community
                        </a>
                    </li>
                </ul>
            </div>
            <a href="{{route('community.all')}}">
                <h3>
                    Communities
                </h3>
            </a>
            @if(!request()->routeIs('community.create') && !request()->routeIs('community.edit'))
                <form class="form-inline mt-4" action="{{url()->current()}}" method="GET">
                    <input class="form-control mr-sm-2" type="search" name="q" placeholder="Search"
                           aria-label="Search" value="{{isset($_GET['q'])?$_GET['q']:''}}">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i
                            class="fas fa-search"></i></button>
                </form>
             @endif
            @include('includes.message')
        </div>
        @hasSection('form')
            <div class="card-body">
                @yield('form')
            </div>
        @else
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @forelse($communities as $community)
                            <div class="subscribe-item">
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="wall-post-img">
                                            <a href="{{route('community.show',['community'=>$community->short_address])}}">
                                                <img src="{{asset($community->logo)}}" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="wall-post-author">
                                            <h6>
                                                <a href="{{route('community.show',['community'=>$community->short_address])}}">
                                                    <h1>{{$community->title}}</h1>
                                                </a>
                                                <a href="{{route('allCommunitySubscribers',['community'=>$community->short_address])}}">
                                                    Subscribers:<span class="blocks"> {{count($community->subscribers)}}</span>
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        @can('isCreator',[App\CommunitySubscriber::class,$community])
                                            <form action="{{route('community.destroy',['community'=>$community])}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100 mt-2">
                                                    <i class="fas fa-trash-alt"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                        @can('isAdmin',[App\CommunitySubscriber::class,$community])
                                            <a href="{{route('community.edit',['community'=>$community])}}" class="btn btn-primary w-100">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                        @endcan
                                        @cannot('isCreator',[App\CommunitySubscriber::class,$community])
                                            @can('subscribe',[App\CommunitySubscriber::class,$community])
                                                <form action="{{route('community.sub.store')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="community_id" value="{{$community->id}}">
                                                    <button type="submit" class="btn btn-success w-100 mt-2">
                                                        Subscribe
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{route('community.sub.destroy',['community'=>$community])}}" method="POST">
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
        @endif
    </div>

@endsection
