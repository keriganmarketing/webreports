@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Run Report</h3>
                </div>
                <div class="panel-body">
                    <form action="#" method="post" id="reportForm">
                        {{ csrf_field() }}
                        <select id="company" class="custom-select">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <select id="month" class="custom-select">
                            @for($i = 1; $i < 26; $i++)
                                <option value="/report/{{ Carbon\Carbon::now()->firstOfMonth()->subMonths($i)->year }}/{{ Carbon\Carbon::now()->firstOfMonth()->subMonths($i)->month}}">
                                    {{ Carbon\Carbon::now()->firstOfMonth()->subMonths($i)->format('F, Y') }}
                                </option>
                            @endfor

                        </select>
                        <button class="btn btn-primary" id="reportSubmit">Run report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts.footer')
    <script>
        $(document).ready(function(){
            $('#reportSubmit').on('click', function(){
              var form = $(this).closest('form');
              var company = form.children()[1].value;
              var reportDate = form.children()[2].value;
              form.attr('action', '/' + company + reportDate ).submit();
            });
        });
    </script>
@endsection