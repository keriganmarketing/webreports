@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel-content pb-5 px-3" >
        
        @if(session('status'))
            <div class="alert alert-success mb-4">
                <p class="m-0">{{ session('status') }}</p>
            </div>
        @endif

        <h1 class="text-primary mb-4">Users <a href="/user/add" class="ml-2 btn btn-info px-4 rounded-pill font-weight-bold" >Add a User</a></h1>
        
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
                <a class="btn btn-secondary ml-lg-3 px-4 rounded-pill font-weight-bold" href="/user/{{ $user->id }}/edit" >Edit</a>
                <form action="/user/{{ $user->id }}/delete" method="POST" class="d-inline">
                    {{ csrf_field() }}
                    <button class="btn btn-danger ml-3 px-4 rounded-pill font-weight-bold" >Delete</button>
                </form>
            </div>

        </div>

        @endforeach

    </div>
</div>
@endsection
