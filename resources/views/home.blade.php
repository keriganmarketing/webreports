@extends('layouts.app')

@section('content')
    <report-form :companies="{{ $companies }}" :dates="{{ $dates }}" ></report-form>
@endsection