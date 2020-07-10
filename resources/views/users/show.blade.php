@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel-content pb-5 px-3" >
        <h1 class="text-primary mb-4">Users</h1>
        
        <div class="row border align-items-center bg-primary d-none d-lg-flex" >
            <div class="client-info-heading col-md-6 py-3 font-weight-bold text-white">Name / Email</div>
            <div class="client-info-heading col-md-3 py-3 font-weight-bold text-white">Permissions</div>
            <div class="client-info-heading col-md-3 py-3 font-weight-bold text-white"></div>
        </div>
        
        @foreach( $users as $user )

        <div class="row border border-top-0 align-items-center bg-white shadow" >

            <div class="client-info col-lg-6 pt-3 py-lg-3">
                <a class="font-weight-bold" href="/user/{{ $user->id }}/edit" >{{ $user->name }}</a>
                <p class="m-0 text-sm" ><a href="{{ $user->email }}" >{{ $user->email }}</a></p>
            </div>
            <div class="analytics-info col-lg-3 py-lg-3">
                <p class="m-0">[coming soon]</p>
            </div>
            <div class="client-controls col-lg-3 py-3 text-lg-right">
                <a class="btn btn-secondary ml-lg-3" href="/user/{{ $user->id }}/edit" >Edit</a>
                <a class="btn btn-danger ml-3" href="#" >Delete</a>
            </div>

        </div>


        @endforeach
    </div>
</div>
@endsection
