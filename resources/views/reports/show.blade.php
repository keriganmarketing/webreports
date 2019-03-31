@extends('layouts.report')

@section('content')
    {{-- $finalReport, $company --}}
    <div class="wrapper py-3">
        <div class="container">
            <div class="row align-items-center justify-content-between ">
                <div class="col-md-5 col-lg-4 col-xl-3 p-3 text-center">
                    <img src="/img/kma-logo.png" alt="KMA" class="img-fluid" width="250">
                </div>
                <div class="col-auto p-3 pt-4 flex-grow-1">
                    <p class="text-center text-md-right text-primary display-2"><strong>{{ $company->name }}</strong></p>
                    <p class="text-center text-md-right text-secondary">{{ Carbon\Carbon::create($finalReport->year, $finalReport->month, 1)->format('F Y') }} Website Statistics Report</p>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col p-2 text-center bg-primary text-white">
                    <p class="display-1">Audience Overview</p>
                    <p class="display-4">{{ Carbon\Carbon::create($finalReport->year, $finalReport->month, 1)->format('F Y') }}
                        vs
                        @if($finalReport->comparedWithLastMonth)
                            {{ Carbon\Carbon::create($finalReport->year, $finalReport->month - 1, 1)->format('F Y') }}
                        @else
                            {{ Carbon\Carbon::create($finalReport->year - 1, $finalReport->month, 1)->format('F Y') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Average Daily Sessions</p>
                    <hr>
                    @php
                        if($finalReport->percent_change_sessions < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">{{ $finalReport->percent_change_sessions }}%</span></p>
                    <p>{{ number_format($finalReport->current_average_daily_sessions, 2, '.', ',') }}
                        <br>
                        vs
                        <br>
                        {{ number_format($finalReport->previous_average_daily_sessions, 2, '.', ',') }}
                    </p>
                    <p class="description">
                        Total number of sessions within the date range. <br>
                        A session is the period of time a user is actively engaged with your website.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Users</p>
                    <hr>
                    @php
                        if($finalReport->percent_change_users < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">{{ $finalReport->percent_change_users }}%</span></p>
                    <p>{{ number_format($finalReport->current_users, 0, '.', ',') }}
                        <br>
                        vs
                        <br>
                        {{ number_format($finalReport->previous_users, 0, '.', ',') }}
                    </p>
                    <p class="description">
                        Users that have had at least one session within the selected date range. <br>
                        Includes both new and returning users.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Page Views</p>
                    <hr>
                    @php
                        if($finalReport->percent_change_page_views < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">{{ $finalReport->percent_change_page_views }}%</span></p>
                    <p>{{ number_format($finalReport->current_page_views, 0, '.', ',') }}
                        <br>
                        vs
                        <br>
                        {{ number_format($finalReport->previous_page_views, 0, '.', ',') }}
                    </p>
                    <p class="description">
                        The total number of pages viewed within the date range.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Pages Per Session</p>
                    <hr>
                    @php
                        if($finalReport->percent_change_pages_per_session < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">{{ $finalReport->percent_change_pages_per_session }}%</span></p>
                    <p>{{ $finalReport->current_pages_per_session }}
                        <br>
                        vs
                        <br>
                        {{ $finalReport->previous_pages_per_session }}
                    </p>
                    <p class="description">
                        The average number of pages viewed during a session.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Average Session Duration</p>
                    <hr>
                    @php
                        if($finalReport->percent_change_average_session_duration < 0){
                            $class = 'red';
                        }else{
                            $class = 'green';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">{{ $finalReport->percent_change_average_session_duration }}%</span></p>
                    <p>{{ gmdate("i:s", $finalReport->current_average_session_duration) }}
                        <br>
                        vs
                        <br>
                        {{ gmdate("i:s", $finalReport->previous_average_session_duration) }}
                    </p>
                    <p class="description">
                        The average length of a session.
                    </p>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-2 text-center pt-4">
                    <p class="display-3 attribute-header">Bounce Rate</p>
                    <hr>
                    @php
                        if($finalReport->percent_change_bounce_rate < 0){
                            $class = 'green';
                        }else{
                            $class = 'red';
                        }
                    @endphp
                    <p><span class="big-number {{ $class }}">{{ $finalReport->percent_change_bounce_rate }}%</span></p>
                    <p>{{ $finalReport->current_bounce_rate }}%
                        <br>
                        vs
                        <br>
                        {{ $finalReport->previous_bounce_rate }}%
                    </p>
                    <p class="description">
                        The percentage of single-page visits (i.e. visits in which the person left your site from
                        the entrance page without interacting with the page).
                    </p>
                </div>
            </div>
            <div class="row py-4">
                <div class="col-lg-6 devices-wrapper">
                    <h2 class="p-2 text-center bg-primary text-white">Device Used</h2>
                    <div class="device-images row">
                        <div class="col-md-4 py-2 text-center">
                            <img src="/img/desktop.png" alt="desktop" class="device img img-fluid">
                            <p class="text-center desktop">desktop</p>
                            <hr>
                            <p class="big-number">{{$finalReport->desktop_percentage}}%</p>
                        </div>
                        <div class="col-md-4 py-2 text-center">
                            <img src="/img/phone.png" alt="desktop" class="device img img-fluid phone">
                            <p class="text-center phone-text">mobile</p>
                            <hr>
                            <p class="big-number">{{$finalReport->mobile_percentage}}%</p>
                        </div>
                        <div class="col-md-4 py-2 text-center">
                            <img src="/img/tablet.png" alt="desktop" class="device img img-fluid tablet">
                            <p class="text-center tablet-text">tablet</p>
                            <hr>
                            <p class="big-number">{{$finalReport->tablet_percentage}}%</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="new-vs-returning">
                        <div class="returning col-md-6">
                            <p class="big-number visitors">{{ $finalReport->returning_visitors }}%</p>
                            <hr>
                            <p class="compare-header text-center visitor-type">Returning Visitors</p>
                        </div>
                        <div class="new col-md-6">
                            <p class="big-number visitors">{{ $finalReport->new_visitors }}%</p>
                            <hr>
                            <p class="compare-header text-center visitor-type">New Visitors</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col p-2 text-center bg-primary text-white" >
                    <h2 class="text-center">Traffic Sources</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-center no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Organic Search: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary" >{{ number_format($finalReport->organic_search, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Visitors that found you on Google, Yahoo, Bing, etc.</small></span>
                </div>
                <div class="col-md-6 text-center bg-light no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Email: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($finalReport->email_search, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Users who click to your website from an email</small></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-center bg-light no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Referral: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($finalReport->referral, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Other websites that link to you</small></span>
                </div>
                <div class="col-md-6 text-center no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Social: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($finalReport->social, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Users who click to your website from a social media page or post.</small></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 text-center no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Direct Traffic: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($finalReport->direct_traffic, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Users that type your domain name into their address bar, or have your site saved in their favorites</small></span>
                </div>
                <div class="col-md-6 text-center bg-light no-gutter d-flex flex-wrap align-items-center traffic-source">
                    <span class="col-6 col-xl-4 text-right  display-3 px-2">Paid Search: </span>
                    <span class="col-6 col-xl-3 display-1 text-left text-xl-center text-secondary">{{ number_format($finalReport->paid_search, 1, '.', ',') }}%</span>
                    <span class="text-left col-xl-5 text-center text-xl-left p-2 pt-0 pt-xl-2 flex-grow d-flex align-items-start"><small class="w-100">Users who click to your website from a sponsored listing or display ad.</small></span>
                </div>
            </div>
            <div class="row header mt-5">
                <div class="col p-2 text-center bg-primary text-white" >
                    <h2 class="text-center">Most Visited Pages</h2>
                </div>
            </div>
            @php
                $page_name_array = [
                    $finalReport->most_visited_page_name_1,
                    $finalReport->most_visited_page_name_2,
                    $finalReport->most_visited_page_name_3,
                    $finalReport->most_visited_page_name_4,
                    $finalReport->most_visited_page_name_5,
                    $finalReport->most_visited_page_name_6,
                    $finalReport->most_visited_page_name_7,
                    $finalReport->most_visited_page_name_8,
                    $finalReport->most_visited_page_name_9,
                    $finalReport->most_visited_page_name_10
                ];
                $readable_name_array = [];
                foreach($page_name_array as $name){
                    array_push($readable_name_array, App\Report::determineName($name));
                }
            @endphp
            <div class="row">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        1&nbsp;&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_1 }}" target="_blank">{{ $readable_name_array[0] }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_1 }}%
                    </p>
                </div>
            </div>
            <div class="row bg-light">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        2&nbsp;&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_2 }}" target="_blank">{{ $readable_name_array[1] }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_2 }}%
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        3&nbsp;&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_3 }}" target="_blank">{{  $readable_name_array[2] }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_3 }}%
                    </p>
                </div>
            </div>
            <div class="row bg-light">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        4&nbsp;&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_4 }}" target="_blank">{{  $readable_name_array[3] }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_4 }}%
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        5&nbsp;&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_5 }}" target="_blank">{{  $readable_name_array[4] }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_5 }}%
                    </p>
                </div>
            </div>
            <div class="row bg-light">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        6&nbsp;&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_6 }}" target="_blank">{{  $readable_name_array[5] }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_6 }}%
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        7&nbsp;&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_7 }}" target="_blank">{{ $readable_name_array[6]  }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_7 }}%
                    </p>
                </div>
            </div>
            <div class="row bg-light">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        8&nbsp;&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_8 }}" target="_blank">{{  $readable_name_array[7] }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_8 }}%
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        9&nbsp;&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_9 }}" target="_blank">{{  $readable_name_array[8] }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_9 }}%
                    </p>
                </div>
            </div>
            <div class="row bg-light">
                <div class="col flex-grow col-xl-11 white-right-border">
                    <p class="page-name">
                        10&nbsp;&nbsp;<a href="{{ $company->url . $finalReport->most_visited_page_name_10 }}" target="_blank">{{  $readable_name_array[9]  }}</a>
                    </p>
                </div>
                <div class="col-auto col-xl-1">
                    <p class="page-percentage">
                        {{ $finalReport->most_visited_page_percentage_10 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
