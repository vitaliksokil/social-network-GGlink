@extends('layouts.main')
@section('title', Auth::user()->name .' "'.Auth::user()->nickname.'" '.Auth::user()->surname)
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <h1>settings</h1>
    </div>
@stop
