@if (Auth::user())
<div class="bg-primary d-lg-none" id="mobilenav-wrapper" >
    <div class="sidebar-heading px-3"> 
        <a class="navbar-brand text-info font-weight-bold py-3" href="{{ url('/') }}">
            <img src="/img/kma.png" alt="KMA Web Reports" style="height:36px" >
        </a>
    </div>

    <div class="p-4 border-bottom border-top border-dark">
        @if (Route::current()->getName() != 'home')
            <a class="btn btn-info rounded-pill font-weight-bold btn-block" href="/">Run Report</a>
        @endif
    </div>

    <div class="list-group list-group-flush">
        {{-- <a href="#" class="list-group-item list-group-item-action bg-primary text-white">Dashboard</a> --}}
        
        <a class="list-group-item list-group-item-action bg-dark text-white" href="{{ route('company.create') }}">Add Client</a>
        <a class="list-group-item list-group-item-action bg-dark text-white" href="{{ route('company.show') }}">Manage Clients</a>
        <a class="list-group-item list-group-item-action bg-dark text-white" href="{{ route('users.show') }}">Manage Users</a>
        <a class="list-group-item list-group-item-action bg-dark text-white" href="{{ route('admin.settings') }}">Utilities</a>

    </div>

    

    <div class="mt-4">
        <a class="text-white d-block p-3" href="{{ route('logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">Logout</a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</div>
@endif