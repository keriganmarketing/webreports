@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel-content" >
    <h1 class="text-primary mb-4">Run a Report</h1>

        <report-form 
            :companies="{{ $companies }}" 
            :dates="{{ $dates }}" 
        ></report-form>
    </div>
</div>

@endsection