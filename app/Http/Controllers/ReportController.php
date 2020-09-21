<?php

namespace App\Http\Controllers;

use App\Report;
use App\Helpers;
use App\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Spatie\Analytics\Analytics;
use Spatie\Analytics\AnalyticsClientFactory;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company, $year, $month)
    {
        $helpers   = new Helpers();
        $report    = new Report();
        $companies = Helpers::getCompanies();
        $dates     = Helpers::buildDates();

        // Generate Current Month
        $reportNow = Report::getReport($company->id, $year, $month);
        if (! $reportNow) {
            $reportNow = $this->create($company, $year, $month);
        }

        // See if Current month is good. If not, show error
        if(! $report->reportIsGood($reportNow)){
            return view('reports.nodata', compact('company', 'companies', 'dates', 'helpers'));
        }

        // Generate YAG Comparison
        $reportYag = Report::getReport($company->id, $year - 1, $month);
        if (! $reportYag) {
            $reportYag = $this->create($company, $year - 1, $month);
        }

        // Is YAG accurate? If not, calculate MAG instead
        if(! $report->reportIsGood($reportYag)){
            $reportYag = $this->create($company, $year, $month - 1);

            // Id MAG is not acceptible either, just show month with no comparisons.
            if(! $report->reportIsGood($reportYag)){
                return view('reports.single', compact('reportNow', 'company', 'companies', 'dates', 'helpers'));
            }
        }

        return view('reports.index', compact('reportNow', 'reportYag', 'company', 'companies', 'dates', 'helpers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company, $year, $month)
    {
        $analyticsConfig = [
            'view_id' => $company->viewId,
            'service_account_credentials_json' => storage_path('app/laravel-google-analytics/service-account-credentials.json'),
            'cache_lifetime_in_minutes' => 60 * 24,
            'cache' => [
                'store' => 'file',
            ],
        ];
        $analytics = new AnalyticsClientFactory();
        $analytics = $analytics::createForConfig($analyticsConfig);
        $client = new Analytics($analytics, $company->viewId);
        $report = new Report();

        $currentDates  = $report->determineCurrentMonth($year, $month);
        $daysInMonth = $report->calculateDaysInMonth($year, $month);
        $currentPeriod  = Period::create($currentDates['start'], $currentDates['end']);

        $current               = $report->fetchAnalyticsData($client, $currentPeriod);
        $totalCurrentSessions  = $current['sessions'];

        $deviceCategories  = $report->fetchDeviceCategories($client, $currentPeriod);
        $desktopSessions   = isset($deviceCategories['desktop']) ? $deviceCategories['desktop'] : null;
        $mobileSessions    = isset($deviceCategories['mobile']) ? $deviceCategories['mobile'] : null;
        $tabletSessions    = isset($deviceCategories['tablet']) ? $deviceCategories['tablet'] : null;
        $desktopPercentage = 0.0;
        $mobilePercentage  = 0.0;
        $tabletPercentage  = 0.0;

        if ($desktopSessions) {
            $desktopPercentage = Helpers::calculatePercentage($desktopSessions, $totalCurrentSessions);
        }
        if ($mobileSessions) {
            $mobilePercentage  = Helpers::calculatePercentage($mobileSessions, $totalCurrentSessions);
        }
        if ($tabletSessions) {
            $tabletPercentage  = Helpers::calculatePercentage($tabletSessions, $totalCurrentSessions);
        }

        $paidSearchData = $report->fetchPaidSearchData($client, $currentPeriod);
        $channels       = $report->fetchChannels($client, $currentPeriod);

        // dd($paidSearchData);
        
        if(!$channels || !isset($channels['direct'])) {
            return view('reports.error');
        }

        $directTraffic        = isset($channels['direct']) ? Helpers::calculatePercentage($channels['direct'], $totalCurrentSessions): 0;
        $organicSearchTraffic = isset($channels['organicsearch']) ? Helpers::calculatePercentage($channels['organicsearch'], $totalCurrentSessions) : 0;
        $referralTraffic      = isset($channels['referral']) ? Helpers::calculatePercentage($channels['referral'], $totalCurrentSessions) : 0;
        $socialTraffic        = isset($channels['social']) ? Helpers::calculatePercentage($channels['social'], $totalCurrentSessions) : 0;
        $paidSearch           = Helpers::calculatePercentage($paidSearchData, $totalCurrentSessions);
        $emailSearchTraffic   = isset($channels['email']) ? Helpers::calculatePercentage($channels['email'], $totalCurrentSessions) : 0;

        // dd($channels);

        // $directTraffic        = isset($channels['direct']) ? $channels['direct'] : 0;
        // $organicSearchTraffic = isset($channels['organicsearch']) ? $channels['organicsearch'] : 0;
        // $referralTraffic      = isset($channels['referral']) ? $channels['referral'] : 0;
        // $socialTraffic        = isset($channels['social']) ? $channels['social'] : 0;
        // $paidSearch           = $paidSearchData;
        // $emailSearchTraffic   = isset($channels['email']) ? $channels['email'] : 0;

        

        $topPagesData                     = $report->fetchTopPages($client, $currentPeriod);
        $totalSessions                    = $topPagesData[1];
        $percentNewSessions               = round($current['percentNewSessions'], 2);
        $percentReturningSessions         = 100 - $percentNewSessions;
        $totalCurrentUsers                = $current['users'];
        $totalCurrentPageViews            = $current['pageViews'];
        $totalCurrentPageViewsPerSession  = $current['pageViewsPerSession'];
        $totalCurrentSessionDuration      = $current['avgSessionDuration'];
        $totalCurrentBounceRate           = $current['bounceRate'];
        $currentAverageDailySessions      = ($totalCurrentSessions / $daysInMonth);

        $topPagesData[0] = Report::fixTopPageData($topPagesData[0]);

        $company     = Company::find($company->id);

        

        $finalReport = Report::create([
            'company_id'                              => $company->id,
            'year'                                    => $year,
            'month'                                   => $month,
            'current_average_daily_sessions'          => round($currentAverageDailySessions, 2),
            'previous_average_daily_sessions'         => 0,
            'percent_change_sessions'                 => 0,
            'current_users'                           => round($totalCurrentUsers, 2),
            'previous_users'                          => 0,
            'percent_change_users'                    => 0,
            'current_page_views'                      => round($totalCurrentPageViews, 2),
            'previous_page_views'                     => 0,
            'percent_change_page_views'               => 0,
            'current_pages_per_session'               => round($totalCurrentPageViewsPerSession, 2),
            'previous_pages_per_session'              => 0,
            'percent_change_pages_per_session'        => 0,
            'current_average_session_duration'        => $totalCurrentSessionDuration,
            'previous_average_session_duration'       => 0,
            'percent_change_average_session_duration' => 0,
            'current_bounce_rate'                     => round($totalCurrentBounceRate, 2),
            'previous_bounce_rate'                    => 0,
            'percent_change_bounce_rate'              => 0,
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
            'most_visited_page_percentage_1'          => number_format(Helpers::calculatePercentage($topPagesData[0][0][1], $totalSessions), 2),
            'most_visited_page_name_2'                => $topPagesData[0][1][0],
            'most_visited_page_percentage_2'          => number_format(Helpers::calculatePercentage($topPagesData[0][1][1], $totalSessions), 2),
            'most_visited_page_name_3'                => $topPagesData[0][2][0],
            'most_visited_page_percentage_3'          => number_format(Helpers::calculatePercentage($topPagesData[0][2][1], $totalSessions), 2),
            'most_visited_page_name_4'                => $topPagesData[0][3][0],
            'most_visited_page_percentage_4'          => number_format(Helpers::calculatePercentage($topPagesData[0][3][1], $totalSessions), 2),
            'most_visited_page_name_5'                => $topPagesData[0][4][0],
            'most_visited_page_percentage_5'          => number_format(Helpers::calculatePercentage($topPagesData[0][4][1], $totalSessions), 2),
            'most_visited_page_name_6'                => $topPagesData[0][5][0],
            'most_visited_page_percentage_6'          => number_format(Helpers::calculatePercentage($topPagesData[0][5][1], $totalSessions), 2),
            'most_visited_page_name_7'                => $topPagesData[0][6][0],
            'most_visited_page_percentage_7'          => number_format(Helpers::calculatePercentage($topPagesData[0][6][1], $totalSessions), 2),
            'most_visited_page_name_8'                => $topPagesData[0][7][0],
            'most_visited_page_percentage_8'          => number_format(Helpers::calculatePercentage($topPagesData[0][7][1], $totalSessions), 2),
            'most_visited_page_name_9'                => $topPagesData[0][8][0],
            'most_visited_page_percentage_9'          => number_format(Helpers::calculatePercentage($topPagesData[0][8][1], $totalSessions), 2),
            'most_visited_page_name_10'               => $topPagesData[0][9][0],
            'most_visited_page_percentage_10'         => number_format(Helpers::calculatePercentage($topPagesData[0][9][1], $totalSessions), 2),
            'comparedWithLastMonth'                   => 0
        ]);

        return $finalReport;

    }
}