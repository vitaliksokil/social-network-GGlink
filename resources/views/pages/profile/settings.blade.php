@extends('layouts.main')
@section('title', 'Settings')
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-success">
            {{ session('error') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3>Settings</h3>
                </div>

                <div class="card-body">
                    <div class="card mb-3">
                        <div class="card-header">Profile settings</div>
                        <div class="card-body">
                            <form action="{{route('settings')}}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                    <label for="show_email"
                                           class="col-lg-2 col-form-label">{{ __('Show email?') }}</label>
                                    <div class="col-lg-6">
                                        <select name="show_email"
                                                id="show_email"
                                                class="form-control @error('show_email') is-invalid @enderror"
                                        >
                                            <option value="0" {{$user->show_email == 0 ? 'selected' : ''}}>No</option>
                                            <option value="1" {{$user->show_email == 1 ? 'selected' : ''}}>Yes</option>
                                        </select>
                                        @error('show_email')
                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <button type="reset" class="btn btn-pink">Reset</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Wall settings</div>
                        <div class="card-body">
                            <form action="{{route('settings')}}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                    <label for="wall_can_edit"
                                           class="col-lg-2 col-form-label">{{ __('Who can post to my wall') }}</label>
                                    <div class="col-lg-6">
                                        <select name="wall_can_edit"
                                                id="wall_can_edit"
                                                class="form-control @error('wall_can_edit') is-invalid @enderror"
                                            >
                                            <option value="0" {{$user->wall_can_edit == 0 ? 'selected' : ''}}>Only me</option>
                                            <option value="1" {{$user->wall_can_edit == 1 ? 'selected' : ''}}>My friends and me</option>
                                            <option value="2" {{$user->wall_can_edit == 2 ? 'selected' : ''}}>All users</option>
                                        </select>
                                        @error('wall_can_edit')
                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <button type="reset" class="btn btn-pink">Reset</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
