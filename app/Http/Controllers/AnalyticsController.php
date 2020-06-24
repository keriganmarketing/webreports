<?php

namespace App\Http\Controllers;

use DB;
use Analytics;
use App\Report;
use App\Company;
use Carbon\Carbon;
use Spatie\Analytics\Period;

class AnalyticsController extends Controller
{
    public function checkDatabase(Company $company, $year, $month)
    {
        $finalReport = Report::where([
           ['company_id', '=', $company->id],
           ['year', '=', $year],
           ['month', '=', $month]
        ])->first();

        if (! $finalReport) {
            return $this->runReport($company, $year, $month);
        }

        $companies = Company::all()->sortBy('name');
        $dates = [];
       
        for($i = 1; $i < 26; $i++){
            $dates[] = [
                'year' => Carbon::now()->firstOfMonth()->subMonths($i)->year,
                'month' => Carbon::now()->firstOfMonth()->subMonths($i)->month,
                'name' => Carbon::now()->firstOfMonth()->subMonths($i)->format('F, Y')
            ];
        }

        $dates = json_encode($dates);

        return view('reports.show', compact('finalReport', 'company', 'companies', 'dates'));
    }

    public function runReport(Company $company, $year, $month)
    {
        $client                = Analytics::setViewId($company->viewId);
        $report                = new Report();
        $comparedWithLastMonth = false;

        //determine our dates
        $currentDates  = $report->determineDates($year, $month);
        $previousDates = $report->determineDates($year - 1, $month); //this is ugly

        //How many days in the query?
        $daysInMonth = $report->calculateDaysInMonth($year, $month);
        $daysInMonthYAG = $report->calculateDaysInMonth($year - 1, $month);

        //create date range for query
        $currentPeriod  = Period::create($currentDates['start'], $currentDates['end']);
        $previousPeriod = Period::create($previousDates['start'], $previousDates['end']);

        $current               = $report->fetchAnalyticsData($client, $currentPeriod);
        $previous              = $report->fetchAnalyticsData($client, $previousPeriod);
        $totalCurrentSessions  = $current['sessions'];
        $totalPreviousSessions = $previous['sessions'];

        $deviceCategories  = $report->fetchDeviceCategories($client, $currentPeriod);
        $desktopSessions   = isset($deviceCategories['desktop']) ? $deviceCategories['desktop'] : null;
        $mobileSessions    = isset($deviceCategories['mobile']) ? $deviceCategories['mobile'] : null;
        $tabletSessions    = isset($deviceCategories['tablet']) ? $deviceCategories['tablet'] : null;
        $desktopPercentage = 0.0;
        $mobilePercentage  = 0.0;
        $tabletPercentage  = 0.0;


        if ($desktopSessions) {
            $desktopPercentage = $this->calculatePercentage($desktopSessions, $totalCurrentSessions);
        }
        if ($mobileSessions) {
            $mobilePercentage  = $this->calculatePercentage($mobileSessions, $totalCurrentSessions);
        }
        if ($tabletSessions) {
            $tabletPercentage  = $this->calculatePercentage($tabletSessions, $totalCurrentSessions);
        }

        $paidSearchData = $report->fetchPaidSearchData($client, $currentPeriod);
        $channels       = $report->fetchChannels($client, $currentPeriod);
        
        if(!$channels || !isset($channels['direct'])) {
            return view('reports.error');
        }

        $directTraffic        = $this->calculatePercentage($channels['direct'], $totalCurrentSessions);
        $organicSearchTraffic = $this->calculatePercentage($channels['organicsearch'], $totalCurrentSessions);
        $referralTraffic      = isset($channels['referral']) ? $this->calculatePercentage($channels['referral'], $totalCurrentSessions) : '0';
        $socialTraffic        = isset($channels['social']) ? $this->calculatePercentage($channels['social'], $totalCurrentSessions) : 0;
        $paidSearch           = $this->calculatePercentage($paidSearchData, $totalCurrentSessions);
        $emailSearchTraffic   = 100 - ($directTraffic + $organicSearchTraffic + $referralTraffic + $socialTraffic + $paidSearch);

        if ($emailSearchTraffic < .2) {
            //we'll just round this down to account for any other rounding errors
            $emailSearchTraffic = 0;
        }

        $topPagesData                     = $report->fetchTopPages($client, $currentPeriod);
        $totalSessions                    = $topPagesData[1];

        $percentNewSessions               = round($current['percentNewSessions'], 2);
        $percentReturningSessions         = 100 - $percentNewSessions;

        $totalCurrentUsers                = $current['users'];
        $totalPreviousUsers               = $previous['users'];

        $totalCurrentPageViews            = $current['pageViews'];
        $totalPreviousPageViews           = $previous['pageViews'];

        $totalCurrentPageViewsPerSession  = $current['pageViewsPerSession'];
        $totalPreviousPageViewsPerSession = $previous['pageViewsPerSession'];

        $totalCurrentSessionDuration      = $current['avgSessionDuration'];
        $totalPreviousSessionDuration     = $previous['avgSessionDuration'];

        $totalCurrentBounceRate           = $current['bounceRate'];
        $totalPreviousBounceRate          = $previous['bounceRate'];

        //get average daily session for current month
        $currentAverageDailySessions      = ($totalCurrentSessions / $daysInMonth);
        $previousAverageDailySessions      = ($totalPreviousSessions / $daysInMonthYAG);
        
        // +1 to account for leap year
        $notEnougData = ($daysInMonthYAG + 1) < $daysInMonth || $previousAverageDailySessions < 1;

        if ($notEnougData) {
            $previousMonth                    = $month - 1;
            $previousAnalyticsData            = $this->compareWithLastMonth($client, $report, $year, $previousMonth);
            $totalPreviousSessions            = $previousAnalyticsData['rows'][0][0];
            $totalPreviousUsers               = $previousAnalyticsData['rows'][0][1];
            $totalPreviousPageViews           = $previousAnalyticsData['rows'][0][2];
            $totalPreviousPageViewsPerSession = $previousAnalyticsData['rows'][0][3];
            $totalPreviousSessionDuration     = $previousAnalyticsData['rows'][0][4];
            $totalPreviousBounceRate          = $previousAnalyticsData['rows'][0][5];
            $daysInMonth                      = $report->calculateDaysInMonth($year, $previousMonth);
            $comparedWithLastMonth            = true;
        }

        //get previous average daily sessions
        $previousAverageDailySessions = ($totalPreviousSessions / $daysInMonth);

        //calculate the percent change
        $percentChangeSessions            = $this->percentChange($currentAverageDailySessions, $previousAverageDailySessions);
        $percentChangeUsers               = $this->percentChange($totalCurrentUsers, $totalPreviousUsers);
        $percentChangePageViews           = $this->percentChange($totalCurrentPageViews, $totalPreviousPageViews);
        $percentChangePageViewsPerSession = $this->percentChange($totalCurrentPageViewsPerSession, $totalPreviousPageViewsPerSession);
        $percentChangeSessionDuration     = $this->percentChange($totalCurrentSessionDuration, $totalPreviousSessionDuration);
        $percentChangeBounceRate          = $this->percentChange($totalCurrentBounceRate, $totalPreviousBounceRate);

        $company     = Company::find($company->id);

        // TODO: fix this garbage
        /************************************************************/

        for ($i = 0; $i < 10; $i ++) {
            if (! isset($topPagesData[0][$i])) {
                $topPagesData[0][$i] = '/No-Data';
                for ($k = 0; $k < 10; $k++) {
                    if (!isset($topPagesData[0][ $i ][ $k ])) {
                        $topPagesData[0][ $i ][ $k ] = '/No-Data';
                    }
                }
            }
        }

        /*************************************************************/

        $finalReport = Report::create([
            'company_id'                              => $company->id,
            'year'                                    => $year,
            'month'                                   => $month,
            'current_average_daily_sessions'          => round($currentAverageDailySessions, 2),
            'previous_average_daily_sessions'         => round($previousAverageDailySessions, 2),
            'percent_change_sessions'                 => round($percentChangeSessions, 2),
            'current_users'                           => round($totalCurrentUsers, 2),
            'previous_users'                          => round($totalPreviousUsers, 2),
            'percent_change_users'                    => round($percentChangeUsers, 2),
            'current_page_views'                      => round($totalCurrentPageViews, 2),
            'previous_page_views'                     => round($totalPreviousPageViews, 2),
            'percent_change_page_views'               => round($percentChangePageViews, 2),
            'current_pages_per_session'               => round($totalCurrentPageViewsPerSession, 2),
            'previous_pages_per_session'              => round($totalPreviousPageViewsPerSession, 2),
            'percent_change_pages_per_session'        => round($percentChangePageViewsPerSession, 2),
            'current_average_session_duration'        => $totalCurrentSessionDuration,
            'previous_average_session_duration'       => $totalPreviousSessionDuration,
            'percent_change_average_session_duration' => round($percentChangeSessionDuration, 2),
            'current_bounce_rate'                     => round($totalCurrentBounceRate, 2),
            'previous_bounce_rate'                    => round($totalPreviousBounceRate, 2),
            'percent_change_bounce_rate'              => round($percentChangeBounceRate, 2),
            'desktop_percentage'                      => $desktopPercentage,
            'mobile_percentage'                       => isset($mobilePercentage) ? $mobilePercentage : 0,
            'tablet_percentage'                       => $tabletPercentage,
            'new_visitors'                            => $percentNewSessions,
            'returning_visitors'                      => $percentReturningSessions,
            'organic_search'                          => $organicSearchTraffic,
            'referral'                                => $referralTraffic,
            'direct_traffic'                          => $directTraffic,
            'email'                                   => $emailSearchTraffic,
            'social'                                  => $socialTraffic,
            'paid_search'                             => $paidSearch,
            'most_visited_page_name_1'                => $topPagesData[0][0][0],
            'most_visited_page_percentage_1'          => number_format($this->calculatePercentage($topPagesData[0][0][1], $totalSessions), 2),
            'most_visited_page_name_2'                => $topPagesData[0][1][0],
            'most_visited_page_percentage_2'          => number_format($this->calculatePercentage($topPagesData[0][1][1], $totalSessions), 2),
            'most_visited_page_name_3'                => $topPagesData[0][2][0],
            'most_visited_page_percentage_3'          => number_format($this->calculatePercentage($topPagesData[0][2][1], $totalSessions), 2),
            'most_visited_page_name_4'                => $topPagesData[0][3][0],
            'most_visited_page_percentage_4'          => number_format($this->calculatePercentage($topPagesData[0][3][1], $totalSessions), 2),
            'most_visited_page_name_5'                => $topPagesData[0][4][0],
            'most_visited_page_percentage_5'          => number_format($this->calculatePercentage($topPagesData[0][4][1], $totalSessions), 2),
            'most_visited_page_name_6'                => $topPagesData[0][5][0],
            'most_visited_page_percentage_6'          => number_format($this->calculatePercentage($topPagesData[0][5][1], $totalSessions), 2),
            'most_visited_page_name_7'                => $topPagesData[0][6][0],
            'most_visited_page_percentage_7'          => number_format($this->calculatePercentage($topPagesData[0][6][1], $totalSessions), 2),
            'most_visited_page_name_8'                => $topPagesData[0][7][0],
            'most_visited_page_percentage_8'          => number_format($this->calculatePercentage($topPagesData[0][7][1], $totalSessions), 2),
            'most_visited_page_name_9'                => $topPagesData[0][8][0],
            'most_visited_page_percentage_9'          => number_format($this->calculatePercentage($topPagesData[0][8][1], $totalSessions), 2),
            'most_visited_page_name_10'               => $topPagesData[0][9][0],
            'most_visited_page_percentage_10'         => number_format($this->calculatePercentage($topPagesData[0][9][1], $totalSessions), 2),
            'comparedWithLastMonth'                   => $comparedWithLastMonth
        ]);

        return view('reports.show', compact('finalReport', 'company'));
    }


    protected function percentChange($newNumber, $oldNumber)
    {
        if (! is_numeric($newNumber) || ! is_numeric($oldNumber)) {
            return 'No Data';
        }
        if ($oldNumber > 0) {
            return ((($newNumber - $oldNumber) / $oldNumber) * 100);
        }

        return 100;
    }

    protected function compareWithLastMonth($client, $report, $year, $month)
    {
        $previousDates         = $report->determineDates($year, $month);
        $previousPeriod        = Period::create($previousDates['start'], $previousDates['end']);
        $previousNumberOfDays  = $report->calculateDaysInMonth($year, $month);
        $previousAnalyticsData = $client->performQuery(
            $previousPeriod,
            'ga:sessions, ga:users, ga:pageViews, ga:pageViewsPerSession, ga:avgSessionDuration, ga:bounceRate'
        );

        return $previousAnalyticsData;
    }

    /**
     * @param $part
     * @param $whole
     * @return float
     */
    protected function calculatePercentage($part, $whole)
    {
        if (! is_numeric($part) || ! is_numeric($whole)) {
            return 0;
        }
        return round(($part / $whole) * 100, 2);
    }

    public function masterReport($year, $month)
    {
        $currentSessions              = $this->sumData($year, $month, 'current_average_daily_sessions');
        $previousSessions             = $this->sumData($year, $month, 'previous_average_daily_sessions');
        $percentChangeSessions        = round($this->percentChange($currentSessions, $previousSessions), 2);
        $currentUsers                 = $this->sumData($year, $month, 'current_users');
        $previousUsers                = $this->sumData($year, $month, 'previous_users');
        $percentChangeUsers           = round($this->percentChange($currentUsers, $previousUsers), 2);
        $currentPageViews             = $this->sumData($year, $month, 'current_page_views');
        $previousPageViews            = $this->sumData($year, $month, 'previous_page_views');
        $percentChangePageViews       = round($this->percentChange($currentPageViews, $previousPageViews), 2);
        $currentPagesPerSession       = $this->avgData($year, $month, 'current_pages_per_session');
        $previousPagesPerSession      = $this->avgData($year, $month, 'previous_pages_per_session');
        $percentChangePagesPerSession = round($this->percentChange($currentPagesPerSession, $previousPagesPerSession), 2);
        $currentSessionDuration       = $this->avgData($year, $month, 'current_average_session_duration');
        $previousSessionDuration      = $this->avgData($year, $month, 'previous_average_session_duration');
        $percentChangeSessionDuration = round($this->percentChange($currentSessionDuration, $previousSessionDuration), 2);

        dd($percentChangeSessionDuration);
    }

    /**
     * @param $year
     * @param $month
     * @param $column
     * @return mixed
     */
    protected function sumData($year, $month, $column)
    {
        $sum = DB::table('reports')->where([
            ['year', '=', $year],
            ['month', '=', $month]
        ])->sum($column);

        return $sum;
    }

    protected function avgData($year, $month, $column)
    {
        $average = DB::table('reports')->where([
           ['year',  '=' , $year],
           ['month', '=' , $month]
        ])->avg($column);

        return $average;
    }
}
