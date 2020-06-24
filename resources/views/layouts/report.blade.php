<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KMA Web Reports') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">

        @if (Auth::user())
        <div class="d-flex flex-column flex-md-row align-items-center px-md-4 mb-3 bg-white border-bottom shadow-sm">
            <div class="mr-auto">
                <report-form 
                    :companies="{{ $companies }}" 
                    :dates="{{ $dates }}" 
                    :selected="{{ $company->id }}"
                ></report-form>
            </div>
            <nav class="my-md-0 ml-md-auto mr-md-3 text-center">
                <a class="p-2 py-md-4 text-secondary d-inline-block font-weight-bold" href="{{ route('company.create') }}">Add Company</a>

                <a class="p-2 py-md-4 text-secondary d-inline-block" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            
            </nav>
        </div>
        @endif

        @yield('content')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts.footer')
</body>
</html>