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
<body class="bg-light">
    <div id="app">
        <div class="d-flex flex-column flex-md-row align-items-center px-md-4 mb-3 bg-white border-bottom shadow-sm">
            <!-- Branding Image -->
            <a class="navbar-brand text-info font-weight-bold py-3" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            <nav class="my-md-0 ml-md-auto mr-md-3 text-center">

                @if (Auth::guest())
                    <a class="p-2 py-md-4 text-secondary d-inline-block" href="{{ route('login') }}">Login</a>
                    {{-- <a class="p-2 py-md-4 text-secondary d-inline-block" href="{{ route('register') }}">Register</a> --}}
                @else
                    {{-- <span class="p-2 text-dark"> Hello 
                        {{ Auth::user()->name }}!
                    </span> --}}

                    <a class="p-2 py-md-4 text-secondary d-inline-block font-weight-bold" href="{{ route('company.create') }}">Add Company</a>
                    <a class="p-2 py-md-4 text-secondary d-inline-block font-weight-bold" href="/">Run&nbsp;Report</a>

                    <a class="p-2 py-md-4 text-secondary d-inline-block" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @endif

            </nav>

        </div>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts.footer')
</body>
</html>
