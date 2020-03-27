@extends('layouts.main')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row justify-content-start">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Change password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('updatePassword') }}">
                        <input type="hidden" name="_method" value="PUT">
                        @csrf

                        <div class="form-group row">
                            <label for="currentPassword" class="col-md-4 col-form-label text-md-right">{{ __('Your current password') }}</label>

                            <div class="col-md-3">
                                <input id="currentPassword" type="password" class="form-control @error('currentPassword') is-invalid @enderror" name="currentPassword" value="{{ old('currentPassword') }}" required autocomplete="currentPassword" autofocus>
                                @error('currentPassword')
                                <span class="red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Your new password') }}</label>

                            <div class="col-md-3">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password" autofocus>
                                @error('password')
                                <span class="red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Confirm your new password') }}</label>

                            <div class="col-md-3">
                                <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="{{ old('password_confirmation') }}" required autocomplete="password_confirmation" autofocus>
                                @error('password_confirmation')
                                <span class="red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>



                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm email') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
