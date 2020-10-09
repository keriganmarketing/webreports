@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel-content" >
        <h1 class="text-primary mb-4">Utilities</h1>
        <h2>Build Trend Reports</h2>
        <p>Until we set up a cron, we'll need to run this manually. Reports cannot be overwritten unless manually deleted from the database, so don't run it on partial months.</p>
        <trend-builder-form
            :companies="{{ $companies }}" 
        ></trend-builder-form>

        <h2 class="mt-4">Build Trend Data for a Company</h2>
        <trend-form
            :companies="{{ $companies }}" 
        ><trend-form>
    </div>
</div>
@endsection
