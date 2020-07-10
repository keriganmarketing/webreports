@extends('layouts.app')

@section('content')
    {{-- $finalReport, $company --}}
    <div class="wrapper py-3">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 py-4 text-center">
                    <p class="display-1">Config Error</p>
                    <p>There was anerror when attempting to contact Google Analytics. Check that KMA has access to the account and the property ID has been set correctly.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
