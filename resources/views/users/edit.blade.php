@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel-content" >
        <h1 class="text-primary mb-4">Editing {{ $user->name }}</h1>
        <form class="form-horizontal" role="form" method="POST" action="/user/{{ $user->id }}/edit">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="control-label">Name</label>

                    <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required>

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="control-label">Email Address</label>

                    <input id="email" type="text" class="form-control" name="email" value="{{ $user->email }}" required>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group mt-3 pt-3">
                    <button type="submit" class="btn btn-primary">
                        Edit User
                    </button>
            </div>
        </form>

    </div>
</div>
@endsection
