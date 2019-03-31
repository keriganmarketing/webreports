@extends('layouts.report')

@section('content')
    {{-- $finalReport, $company --}}
    <div class="wrapper py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 py-4">
                <p>There is a configuration problem with this company. Check the following setup requirements:</p>
                <ul>
                    <li>The correct Google Analytics view ID has been configured.</li>
                    <li>The correct Google service account has been assigned read permissions in Google Analytics.</li>
                </ul>
                <p><a class="btn btn-primary" href="/">Run a new report</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
