@extends('layouts.main')
@section('title',ucfirst($room->game->title) .' room #'.$room->id)
@section('content')
    <room
        :room="{{json_encode($room)}}"
        :game_short_address="{{json_encode($room->game->short_address)}}"
        :in-team-members="{{json_encode($inTeamMembers)}}"
        :joined-members="{{json_encode($joinedMembers)}}"
        :messages="{{json_encode($messages)}}"
    ></room>
@endsection
