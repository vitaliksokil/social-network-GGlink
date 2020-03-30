@extends('pages.games.gamesAll')
@section('form')
    <div class="row justify-content-start">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Add new game') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('game.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="title" class="col-md-2 col-form-label ">{{ __('Game title') }}</label>
                            <div class="col-md-10">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus>
                                @error('title')
                                <span class="red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="short_address" class="col-md-2 col-form-label ">{{ __('Game short address') }}</label>
                            <div class="col-md-10">
                                <input id="short_address" type="text" class="form-control @error('short_address') is-invalid @enderror" name="short_address" value="{{ old('short_address') }}" required autocomplete="short_address" autofocus>
                                @error('short_address')
                                <span class="red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="info" class="col-md-2 col-form-label ">{{ __('Game info') }}</label>
                            <div class="col-md-10">
                                <textarea class="@error('info') is-invalid @enderror" name="info" id="editor">{{ old('info') }}</textarea>
                                @error('info')
                                <span class="red" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="logo"
                                   class="col-md-2 col-form-label ">
                                {{ __('Upload logo') }}
                                <i class="fas fa-info-circle orange" title="The photo should be 300x300px otherwise it will be cropped to this resolution."></i>
                            </label>
                            <div class="col-md-10">
                                <input id="logo" type="file"
                                       class="form-control @error('logo') is-invalid @enderror"
                                       name="logo" required autofocus>
                                @error('logo')
                                <span class="invalid-feedback" role="alert">
                                         <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="poster"
                                   class="col-md-2 col-form-label ">{{ __('Upload poster') }} <i class="fas fa-info-circle orange" title="The photo should be 1090x300px(width,height) otherwise it will be cropped to this resolution."></i></label>
                            <div class="col-md-10">
                                <input id="poster" type="file"
                                       class="form-control @error('poster') is-invalid @enderror"
                                       name="poster" required autofocus>
                                @error('poster')
                                <span class="invalid-feedback" role="alert">
                                         <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 ">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
