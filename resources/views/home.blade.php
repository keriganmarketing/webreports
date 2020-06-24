@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center py-4">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Run Web Report</div>

                <div class="card-body">
                    <report-form 
                        :companies="{{ $companies }}" 
                        :dates="{{ $dates }}" 
                    ></report-form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection