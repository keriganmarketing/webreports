
<div id="topbar" class="d-flex align-items-center px-4 bg-white border-bottom shadow-sm">

    @if (Auth::user())
        <span class="fa fa-bars display-1 pointer d-none d-lg-inline-block py-2" @click="toggleSidebar()" ></span>
        <span class="fa fa-bars display-1 pointer d-inline-block d-lg-none py-2" @click="toggleMobileNav()" ></span>

        @if (Route::current()->getName() == 'report')
        <div class="mr-auto ml-4">
            <report-form 
                class="d-none d-lg-flex"
                :companies="{{ $companies }}" 
                :dates="{{ $dates }}" 
                {{ isset($company->id) ? ':selected="'.$company->id.'"' : '' }}
            ></report-form>
        </div>
        @endif

        <nav class="my-md-0 ml-auto pr3 text-center">

            <span class="p-2 text-dark"> Hello 
                {{ Auth::user()->name }}!
            </span>

        </nav>
    @endif
</div>