@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center py-4">
        <div class="col-md-10">
            <h3>SEM Report</h3>
            <div class="card">
                <div class="card-header">
                    <h3 class="panel-title">Select Company and Month</h3>
                </div>
                <div class="panel-body">
                    <form action="#" method="post" id="reportForm">
                        {{ csrf_field() }}
                        <select id="company">
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <select id="month">
                            @for($i = 1; $i < 26; $i++)
                                <option value="/semreport/{{ Carbon\Carbon::now()->firstOfMonth()->subMonths($i)->year }}/{{ Carbon\Carbon::now()->firstOfMonth()->subMonths($i)->month}}">
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
