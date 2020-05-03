<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="auth-user-id" content="{{ Auth::user()->id }}">
    @endauth

    <title>@yield('title','GG link')</title>
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/png">
    <link rel="stylesheet" href="{{ URL::asset('css/app.css')}}">
</head>
<body>
<div id="app">
@include('includes.header')
@include('includes.sidebar') {{--contains @yield('content')--}}
@include('includes.footer')

    <notifications group="messages" position="bottom left" width="400">
        <template slot="body" slot-scope="props">
            <a :href="`/conversation/${props.item.data.from_user.nickname}/${props.item.data.from_user.id}`" class="new-message-notification ml-5 mb-3">
                <div class="row">
                    <div class="col-lg-3">
                        <img :src="`/${props.item.data.from_user.photo}`" alt="">
                    </div>
                    <div class="col-lg-9">
                        <h3 class="notification-title d-inline" v-html="props.item.data.from_user.nickname"></h3>
                        <span class="close" @click="props.close">
                            <i class="far fa-window-close"></i>
                        </span>
                        <hr class="m-0">
                        <div class="notification-content" v-html="props.item.data.message.text"></div>
                    </div>
                </div>
            </a>
        </template>
    </notifications>
</div>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
