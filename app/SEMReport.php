<?php

namespace App;

use Analytics;
use Spatie\Analytics\Period;
use Illuminate\Database\Eloquent\Model;

class SEMReport extends Model
{
    protected $guarded = [];
    protected $table = 'semreports';

    public function execute($client, $period)
    {
        $compareParams = 'ga:adClicks, ga:impressions, ga:CPM, ga:CPC, ga:CTR';
        $otherParams   = [
        'dimensions'   => 'ga:adGroup',
        // 'filters'   => '',
        // 'sort'      => '-ga:adGroup'
        ];
        $data = $client->performQuery($period, $compareParams, $otherParams);

        return $data;
    }
    /**
     * Check the database to see if an SEM report already exists
     * @param  Company $company
     * @param  int  $year
     * @param  int $month
     * @return \Illuminate\Http\Response
     */
    public function previouslyCreated(Company $company, $year, $month)
    {
        $semReport = SEMReport::where([
           ['company_id', '=', $company->id],
           ['year', '=', $year],
           ['month', '=', $month]
        ])->first();

        if (! $semReport) {
            return $this->runReport($company, $year, $month);
        }

        return view('reports.show', compact('semReport', 'company'));
    }

    /**
     * Build search with the given parameters
     * @param  Company $company [description]
     * @param  int $year
     * @param  int $month
     * @return \Illuminate\Http\Response
     */
    public function run(Company $company, $year, $month)
    {
        $client    = Analytics::setViewId($company->viewId);
        $report    = new Report();
        $semreport = new SEMReport();

        // determine our dates
        $currentDates  = $report->determineDates($year, $month);

        // How many days in the query? We'll need this for our math later.
        $daysInMonth   = $report->calculateDaysInMonth($year, $month);

        // create date range for query
        $currentPeriod = Period::create($currentDates['start'], $currentDates['end']);

        // Do it!
        $current       = $semreport->execute($client, $currentPeriod);

        dd($current);
    }
}
