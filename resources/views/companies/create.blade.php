@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create Company</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="/company">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Company Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('viewId') ? ' has-error' : '' }}">
                            <label for="viewId" class="col-md-4 control-label">View ID</label>

                            <div class="col-md-6">
                                <input id="viewId" type="text" class="form-control" name="viewId" value="{{ old('viewId') }}" required>

                                @if ($errors->has('viewId'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('viewId') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="url" class="col-md-4 control-label">URL</label>
                            <div class="col-md-6">
                                <input id="url" type="text" class="form-control" name="url" value="{{ old('url') }}" required>

                                @if ($errors->has('viewId'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
