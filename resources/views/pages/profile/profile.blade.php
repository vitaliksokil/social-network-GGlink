@extends('layouts.main')
@section('title', Auth::user()->name .' "'.Auth::user()->nickname.'" '.Auth::user()->surname)
@section('content')
    <h1>My page</h1>
@stop
