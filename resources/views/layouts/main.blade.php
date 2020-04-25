<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','GG link')</title>
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/png">
    <link rel="stylesheet" href="{{ URL::asset('css/app.css')}}">
</head>
<body>
<div id="app">
@include('includes.header')
@include('includes.sidebar') {{--contains @yield('content')--}}
@include('includes.footer')
</div>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
