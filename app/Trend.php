<?php

namespace App;

use stdClass;
use Exception;
use Carbon\Carbon;
use Spatie\Analytics\Period;
use Spatie\Analytics\Analytics;
use Illuminate\Database\Eloquent\Model;
use Spatie\Analytics\AnalyticsClientFactory;

class Trend extends Model
{
    protected $guarded = [];
    protected $table = 'trends';

    /**
     * Run Google Analytics report with the given parameters
     * @param  Company $company [description]
     * @param  Date $from
     * @param  Date $to
     * @return \Illuminate\Http\Response
     */
    public function runReport(Company $company, $from, $to)
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
        $currentPeriod = Period::create(Carbon::createFromFormat('Y-m-d', $from), Carbon::createFromFormat('Y-m-d', $to));
        $updated = [];

        $trends = $this->fetchAnalyticsData($client, $currentPeriod);
        $report = new stdClass();
        $report->company = $company;
        $report->data = [];

        if($trends){
            foreach($trends as $data){
                if(! $this->exists($company, $data[0])){
                    $store = $this->store($company, $data);
                    $report->data[] = ['updated' => true, 'date' => $data[0]];
                }else{
                    $report->data[] = ['updated' => false, 'date' => $data[0]];
                }
            }
            $report->error = 0;
        }else{
            $report->error = 1;
        }

        $updated[] = $report; 

        return response()->json($updated);
    }

    protected function fetchAnalyticsData($client, Period $period)
    {
        $compareParams = 'ga:sessions, ga:users, ga:pageViews, ga:pageViewsPerSession, ga:avgSessionDuration, ga:bounceRate, ga:percentNewSessions';

        try {
            $data = $client->performQuery(
                $period,
                'ga:sessions',
                [
                    'metrics' => $compareParams,
                    'dimensions' => 'ga:yearMonth'
                ]
            )->rows;

            return $data;

        } catch (Exception $e) {

            // echo 'Caught exception: ',  $e->getMessage(), "\n";

            return [];
        }
    }

    protected function exists($company,$date)
    {
        return Trend::where([
            ['company_id', '=', $company->id],
            ['date', '=', $date]
        ])->first();
    }

    protected function store(Company $company, $data)
    {
        return Trend::create([
            'company_id' => $company->id,
            'date' => $data[0],
            'sessions' => $data[1],
            'users' => $data[2],
            'views' => $data[3],
        ]);
    }
}
