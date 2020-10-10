@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center py-4">
        <div class="col-md-8 mt-md-5">
            <div class="shadow border">
                <div class="bg-primary text-light p-4 px-lg-5">You must log in to continue.</div>
                <div class="bg-light p-4 px-lg-5">
                    <form class="form" role="form" method="POST" action="{{ route('login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="row mb-2" >

                            <div class="form-group col-md-6 {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="text-dark control-label">E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group col-md-6 {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="text-dark control-label">Password</label>
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label text-dark" for="remember">Remember Me</label>
                            </div>
                        </div>

                        <div class="form-group d-flex">
                            <button type="submit" class="btn btn-primary btn-success rounded-pill px-4">
                                Login
                            </button>

                            <a class="btn btn-link text-danger ml-md-auto" href="{{ route('password.request') }}">
                                Forgot Your Password?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
