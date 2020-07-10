@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel-content pb-5 px-3" >
        <h1 class="text-primary mb-4">Clients</h1>

        <div class="row border align-items-center bg-primary d-none d-lg-flex" >
            <div class="client-info-heading col-md-6 py-3 font-weight-bold text-white">Client / Website</div>
            <div class="client-info-heading col-md-3 py-3 font-weight-bold text-white">Google Analytics Property ID</div>
            <div class="client-info-heading col-md-3 py-3 font-weight-bold text-white"></div>
        </div>
        
        @foreach( $companies as $company )

        <div class="row border border-top-0 align-items-center bg-white shadow" >

            <div class="client-info col-lg-6 pt-3 py-lg-3">
                <a class="font-weight-bold" href="/company/{{ $company->id }}/edit" >{{ $company->name }}</a>
                <p class="m-0 text-sm" ><a href="{{ $company->url }}" >{{ $company->url }}</a></p>
            </div>
            <div class="analytics-info col-lg-3 py-lg-3">
                <p class="m-0">{{ $company->viewId }}</p>
            </div>
            <div class="client-controls col-lg-3 py-3 text-lg-right">
                <a class="btn btn-secondary ml-lg-3" href="/company/{{ $company->id }}/edit" >Edit</a>
                <a class="btn btn-danger ml-3" href="#" >Disable</a>
            </div>

        </div>


        @endforeach

    </div>
</div>
@endsection

    {{-- [id] => 3
    [viewId] => 67741511
    [name] => Bone and Joint Clinic
    [url] => https://boneandjointclinicbr.com --}}