@extends('layouts.app')

@section('content')
    {{-- $reportData, $company --}}
    <div class="wrapper py-3 pt-5">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between ">
                
                <div class="col-lg-4 col-lg-4 col-xl-3 text-lg-left pb-3 text-center">
                    <img src="/img/kma-logo.png" alt="KMA" class="img-fluid" width="170">
                </div>

                <div class="col-md-6 col-lg-4 p-3 pt-1">
                    <p class="text-center text-secondary display-3">
                        <strong>{{ $company->name }}</strong><br>
                        <a target="_blank" href="{{ $company->url }}" class="display-4">{{ $company->url }}</a>
                    </p>
                </div>
                
                <div class="col-md-6 col-lg-4 col-xl-3 p-3 pt-1 flex-grow-1">
                    <p class="text-center text-lg-right text-primary display-3">
                        <strong>{{ Carbon\Carbon::create($reportNow->year, $reportNow->month, 1)->format('F Y') }}</strong><br>
                        Website Statistics Report</p>
                </div>

            </div>

            <div class="d-flex flex-wrap mt-2">
                <div class="col-12 p-2 text-center bg-primary text-white">
                    <h2 class="m-0">18-Month Trend</h2>
                    <p class="display-4 m-0">
                        <strong>{{ Carbon\Carbon::now()->subMonths(19)->format('F Y') }}</strong>
                        to
                        <strong>{{ Carbon\Carbon::now()->subMonth()->format('F Y') }}</strong>
                    </p>
                </div>
                <div class="col-12 p-0 mb-4"  >
                    <trend-chart
                        class="bg-white border border shadow py-4 w-100 "
                        :company="{{ $company->id }}"
                        end="{{ Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Ym') }}"
                        start="{{ Carbon\Carbon::now()->subMonths(19)->startOfMonth()->format('Ym') }}"
                    ></trend-chart>
                </div>
                <div class="col-12 p-2 text-center bg-primary text-white">
                    <h2 class="m-0">
                        {{ Carbon\Carbon::create($reportNow->year, $reportNow->month, 1)->format('F Y') }}
                        vs
                        {{ Carbon\Carbon::create($reportYag->year, $reportYag->month, 1)->format('F Y') }}
                    </h2>
                </div>
            </div>
            <div class="d-flex flex-wrap border border shadow pb-4">
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Average Sessions Per Day</p>
                    <hr>
                    @php
                        if(App\Helpers::percentChange($reportNow->current_average_daily_sessions, $reportYag->current_average_daily_sessions) < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">
                        {{ round(App\Helpers::percentChange($reportNow->current_average_daily_sessions, $reportYag->current_average_daily_sessions),2) }}%
                    </span></p>
                    <p>{{ number_format($reportNow->current_average_daily_sessions, 2, '.', ',') }}
                        <br>
                        vs
                        <br>
                        {{ number_format($reportYag->current_average_daily_sessions, 2, '.', ',') }}
                    </p>
                    <p class="description">
                        A session is the period of time a user is actively engaged with your website.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Users</p>
                    <hr>
                    @php
                        if(App\Helpers::percentChange($reportNow->current_users, $reportYag->current_users) < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">
                        {{ round(App\Helpers::percentChange($reportNow->current_users, $reportYag->current_users),2) }}%
                    </span></p>
                    <p>{{ number_format($reportNow->current_users, 0, '.', ',') }}
                        <br>
                        vs
                        <br>
                        {{ number_format($reportYag->current_users, 0, '.', ',') }}
                    </p>
                    <p class="description">
                        Users are individual people that have at least one session on your website.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Page Views</p>
                    <hr>
                    @php
                        if(App\Helpers::percentChange($reportNow->current_page_views, $reportYag->current_page_views) < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">
                        {{ round(App\Helpers::percentChange($reportNow->current_page_views, $reportYag->current_page_views),2) }}%
                    </span></p>
                    <p>{{ number_format($reportNow->current_page_views, 0, '.', ',') }}
                        <br>
                        vs
                        <br>
                        {{ number_format($reportYag->current_page_views, 0, '.', ',') }}
                    </p>
                    <p class="description">
                        The total number of individual pages viewed within the date range.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Pages Per Session</p>
                    <hr>
                    @php
                        if(App\Helpers::percentChange($reportNow->current_pages_per_session, $reportYag->current_pages_per_session) < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">
                        {{ round(App\Helpers::percentChange($reportNow->current_pages_per_session, $reportYag->current_pages_per_session),2) }}%
                    </span></p>
                    <p>{{ $reportNow->current_pages_per_session }}
                        <br>
                        vs
                        <br>
                        {{ $reportYag->current_pages_per_session }}
                    </p>
                    <p class="description">
                        The average number of pages viewed during a session.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Average Session Duration</p>
                    <hr>
                    @php
                        if(App\Helpers::percentChange($reportNow->current_average_session_duration, $reportYag->current_average_session_duration) < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">
                        {{ round(App\Helpers::percentChange($reportNow->current_average_session_duration, $reportYag->current_average_session_duration),2) }}%
                    </span></p>
                    <p>{{ gmdate("i:s", $reportNow->current_average_session_duration) }}
                        <br>
                        vs
                        <br>
                        {{ gmdate("i:s", $reportYag->current_average_session_duration) }}
                    </p>
                    <p class="description">
                        The average length of a session.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Bounce Rate</p>
                    <hr>
                    @php
                        if(App\Helpers::percentChange($reportNow->current_bounce_rate, $reportYag->current_bounce_rate) < 0){
                            $class = 'green';
                        }else{
                            $class = 'red';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">
                        {{ round(App\Helpers::percentChange($reportNow->current_bounce_rate, $reportYag->current_bounce_rate),2) }}%
                        </span></p>
                    <p>{{ $reportNow->current_bounce_rate }}%
                        <br>
                        vs
                        <br>
                        {{ $reportYag->current_bounce_rate }}%
                    </p>
                    <p class="description">
                        The percentage of single-page sessions in which there was no interaction with the page.
                    </p>
                </div>
            </div>
            <div class="d-flex flex-wrap pb-md-4 mt-md-4 ">
                <div class="col-lg-6 devices-wrapper px-0 pr-md-3">
                    <h2 class="p-2 text-center bg-primary text-white m-0">Device Used</h2>
                    <div class="device-images row no-gutters border shadow">
                        <div class="col-md-4 py-2 text-center">
                            <img src="/img/desktop.png" alt="desktop" class="device img img-fluid">
                            <p class="text-center pt-2">desktop / laptop</p>
                            <p class="big-number">{{$reportNow->desktop_percentage}}%</p>
                        </div>
                        <div class="col-md-4 py-2 text-center">
                            <img src="/img/phone.png" alt="desktop" class="device img img-fluid phone">
                            <p class="text-center pt-2">smartphone</p>
                            <p class="big-number">{{$reportNow->mobile_percentage}}%</p>
                        </div>
                        <div class="col-md-4 py-2 text-center">
                            <img src="/img/tablet.png" alt="desktop" class="device img img-fluid tablet">
                            <p class="text-center pt-2">tablet</p>
                            <p class="big-number">{{$reportNow->tablet_percentage}}%</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 px-0 pl-md-3 d-flex flex-column">
                    <h2 class="p-2 text-center bg-primary text-white m-0">New vs Returning Visitors</h2>
                    <div class="new-vs-returning-visitors d-flex flex-column flex-grow-1 border shadow p-4">
                        
                        <div class="row h-50 mb-4 no-gutters ">
                            <div class="new col-6">
                                <p class="big-number visitors">{{ $reportNow->new_visitors }}%</p>
                                <p class="compare-header text-center visitor-type">New Visitors</p>
                            </div>
                            <div class="returning col-6">
                                <p class="big-number visitors">{{ $reportNow->returning_visitors }}%</p>
                                <p class="compare-header text-center visitor-type">Returning Visitors</p>
                            </div>
                        </div>
                        <div class="d-flex" style="border-radius: 20px; box-shadow: inset 0 0 10px rgba(0,0,0,.5); height:40px">
                            <div class="bg-secondary py-2" style="width:{{ $reportNow->new_visitors }}% !important; border-radius: 20px 0 0 20px">&nbsp;</div>
                            <div class="bg-info py-2" style="width:{{ $reportNow->returning_visitors }}% !important; border-radius: 0 20px 20px 0">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header">
                <div class="col p-2 text-center bg-primary text-white" >
                    <h2 class="text-center">Traffic Sources</h2>
                </div>
            </div>
            <div class="d-flex flex-wrap border border shadow">
                <div class="col-md-6 text-center no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Organic Search: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary" >{{ number_format($reportNow->organic_search, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Visitors that found you on Google, Yahoo, Bing, etc.</small></span>
                </div>
                <div class="col-md-6 text-center bg-light no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Email: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($reportNow->email_search, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Users who click to your website from an email</small></span>
                </div>
            
                <div class="col-md-6 text-center bg-light no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Referral: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($reportNow->referral, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Other websites that link to you</small></span>
                </div>
                <div class="col-md-6 text-center no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Social: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($reportNow->social, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Users who click to your website from a social media page, post, or ad.</small></span>
                </div>
            
                <div class="col-md-6 text-center no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Direct Traffic: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($reportNow->direct_traffic, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Users that type your domain name into their address bar, or have your site saved in their favorites</small></span>
                </div>
                <div class="col-md-6 text-center bg-light no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Paid Promotion: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($reportNow->paid_search, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Users who click to your website from a sponsored listing or display ad.</small></span>
                </div>
            </div>
            <div class="header mt-md-4">
                <div class="col p-2 text-center bg-primary text-white" >
                    <h2 class="text-center">Top 10 Visited Pages</h2>
                </div>
            </div>

            <div class="border-top mb-5 shadow" >
            @for($i = 1; $i <= 10; $i++)
            <div class="d-flex align-items-center {{ $i % 2 == 0 ? 'bg-light' : 'bg-white' }} border-left border-right">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        {{$i}}&nbsp;&nbsp;&nbsp;
                        <a 
                            href="{{ $company->url . $reportNow->{ 'most_visited_page_name_' . $i } }}" 
                            target="_blank"
                        >{{ App\Report::determineName($reportNow->{ 'most_visited_page_name_' . $i }) }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $reportNow->{ 'most_visited_page_percentage_' . $i } }}%
                    </p>
                </div>
            </div>
            @endfor
            </div>
        </div>
    </div>
@endsection
