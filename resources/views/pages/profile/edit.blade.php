@extends('layouts.main')
@section('title', 'Edit')
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3>
                        Edit profile info
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="profile-page">
                                <div class="profile-img">
                                    <img
                                        src="{{asset($user->photo)}}"
                                        alt="">
                                </div>
                                <button class="btn btn-success" style="
                                        width: 300px;
                                        border-top-left-radius: 0;
                                        border-top-right-radius:0"
                                        data-toggle="modal" data-target="#uploadPhoto">
                                    <i class="fas fa-upload"></i>
                                    Update photo
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="uploadPhoto" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center">
                                                    Please upload a photo so your friends can recognize you.
                                                    We support JPG,GIF and PNG files. <span class="blocks">The photo should be 300x300px otherwise it will be cropped.</span>
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('updatePhoto')}}" method="POST"
                                                      enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <label for="photo"
                                                               class="col-md-4 col-form-label text-md-right">{{ __('Upload photo') }}</label>
                                                        <div class="col-md-6">
                                                            <input id="photo" type="file"
                                                                   class="form-control @error('photo') is-invalid @enderror"
                                                                   name="photo" required autofocus>
                                                            @error('photo')
                                                            <span class="invalid-feedback" role="alert">
                                                     <strong>{{ $message }}</strong>
                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">
                                                            Close
                                                        </button>
                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th scope="col">ID</th>
                                    <td>{{$user->id}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Name</th>
                                    <td>{{$user->name}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Nickname</th>
                                    <td>{{$user->nickname}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Surname</th>
                                    <td>{{$user->surname}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Email</th>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">About</th>
                                    <td>{!! $user->about !!}</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="buttons d-flex flex-column">
                                <button class="btn btn-success" data-toggle="modal" data-target="#changeProfileInfo">
                                    <i class="fas fa-pen"></i>
                                    Change some info
                                </button>
                                <a class="btn btn-primary" href="{{route('editEmail')}}">
                                    <i class="fas fa-at"></i>
                                    Change email
                                </a>
                                <a class="btn btn-danger" href="{{route('editPassword')}}">
                                    <i class="fas fa-key"></i>
                                    Change password
                                </a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="changeProfileInfo" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content ">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-center">
                                                Change your profile info
                                            </h5>
                                            <button type="button" class="close" data-id='changeProfileInfo'
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{route('update')}}" method="POST">
                                                <input type="hidden" name="_method" value="PUT">
                                                @csrf
                                                <div class="form-group row">
                                                    <label for="name"
                                                           class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                                    <div class="col-md-6">
                                                        <input id="name" type="text"
                                                               class="form-control @error('name') is-invalid @enderror"
                                                               name="name"
                                                               value="{{ $user->name }}" required autocomplete="name"
                                                               autofocus>

                                                        @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="nickname"
                                                           class="col-md-4 col-form-label text-md-right">{{ __('Nickname') }}</label>

                                                    <div class="col-md-6">
                                                        <input id="nickname" type="text"
                                                               class="form-control @error('nickname') is-invalid @enderror"
                                                               name="nickname" value="{{ $user->nickname }}" required
                                                               autocomplete="nickname" autofocus>

                                                        @error('nickname')
                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="surname"
                                                           class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>

                                                    <div class="col-md-6">
                                                        <input id="surname" type="text"
                                                               class="form-control @error('surname') is-invalid @enderror"
                                                               name="surname" value="{{ $user->surname }}" required
                                                               autocomplete="surname" autofocus>

                                                        @error('surname')
                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="editor"
                                                           class="col-md-4 col-form-label text-md-right">{{ __('About you') }}</label>

                                                    <div class="col-md-6">
                                        <textarea name="about" id="editor"
                                                  class="form-control @error('about') is-invalid @enderror"
                                                  required
                                                  autocomplete="about" autofocus>{!! $user->about !!}</textarea>
                                                        @error('about')
                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="password"
                                                           class="col-md-4 col-form-label text-md-right">{{ __('Your password') }}</label>

                                                    <div class="col-md-6">
                                                        <input id="password" type="password"
                                                               class="form-control @error('password') is-invalid @enderror"
                                                               name="password" value="{{ old('password') }}" required
                                                               autocomplete="password" autofocus>
                                                        @error('password')
                                                        <span class="red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                            data-id='changeProfileInfo'>
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
