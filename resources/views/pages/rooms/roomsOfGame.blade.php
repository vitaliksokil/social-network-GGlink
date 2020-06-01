@extends('layouts.main')
@section('title',$game->title .'\'s rooms')
@section('content')
<rooms-of-game
    :game="{{json_encode($game)}}"
    :rooms="{{json_encode($rooms)}}"
    :auth-user-room="{{json_encode($authUserRoom)}}"

    @if(isset($myRoomGameShortAddress))
      :my-room-game-short-address="{{json_encode($myRoomGameShortAddress)}}"
    @endif

></rooms-of-game>
@endsection
