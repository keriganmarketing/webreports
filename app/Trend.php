<?php

namespace App;

use Analytics;
use Carbon\Carbon;
use Exception;
use Spatie\Analytics\Period;
use Illuminate\Database\Eloquent\Model;

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
        $client = Analytics::setViewId($company->viewId);
        $currentPeriod = Period::create(Carbon::createFromFormat('Y-m-d', $from), Carbon::createFromFormat('Y-m-d', $to));
        $updated = [];

        foreach($this->fetchAnalyticsData($client, $currentPeriod) as $data){
            if(! $this->exists($company, $data[0])){
                $updated[] = $this->store($company, $data);      
            }
        }

        return $updated;
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

            echo 'Caught exception: ',  $e->getMessage(), "\n";

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
