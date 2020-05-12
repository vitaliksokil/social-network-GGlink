@extends('layouts.main')
@section('title','Messages')
@section('content')
    <chat :auth-user="{{json_encode(Auth::user())}}" :messages="{{json_encode($messages)}}"></chat>
@endsection
