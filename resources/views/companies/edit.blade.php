@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel-content" >
        <h1 class="text-primary mb-4">Editing {{ $company->name }}</h1>
        <form class="form-horizontal" role="form" method="POST" action="/company/{{ $company->id }}/edit">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="control-label">Client Name</label>

                    <input id="name" type="text" class="form-control" name="name" value="{{ $company->name }}" required>

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group{{ $errors->has('viewId') ? ' has-error' : '' }}">
                <label for="viewId" class="control-label">Google Analytics Property ID</label>

                    <input id="viewId" type="text" class="form-control" name="viewId" value="{{ $company->viewId }}" required>

                    @if ($errors->has('viewId'))
                        <span class="help-block">
                            <strong>{{ $errors->first('viewId') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                <label for="url" class="control-label">URL</label>
                    <input id="url" type="text" class="form-control" name="url" value="{{ $company->url }}" required>

                    @if ($errors->has('viewId'))
                        <span class="help-block">
                            <strong>{{ $errors->first('url') }}</strong>
                        </span>
                    @endif
            </div>
            <div class="form-group mt-3 pt-3">
                    <button type="submit" class="btn btn-primary">
                        Edit Client
                    </button>
            </div>
        </form>

    </div>
</div>
@endsection
