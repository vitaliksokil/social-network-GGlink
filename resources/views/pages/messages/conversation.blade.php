@extends('layouts.main')
@section('title','Messages')
@section('content')
    <conversation :messages="{{$messages}}" :auth-user="{{json_encode(Auth::user())}}" :user-conversation-with="{{json_encode($userConversationWith)}}"></conversation>
@endsection
