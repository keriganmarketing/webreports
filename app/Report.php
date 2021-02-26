<?php

namespace App;

use Carbon\Carbon;
use Spatie\Analytics\Period;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public static function getReport($companyId, $year, $month)
    {
        return Report::where([
            ['company_id', '=', $companyId],
            ['year', '=', $year],
            ['month', '=', $month]
        ])->first();
    }

    public static function fixTopPageData($topPagesData = [])
    {
        for ($i = 0; $i < 10; $i ++) {
            if (! isset($topPagesData[$i])) {
                $topPagesData[$i][0] = '/No-Data';
                $topPagesData[$i][1] = 0;
            }
        }

        return $topPagesData;
    }

    public function determineCurrentMonth($year, $month)
    {
        return [
            'start' => Carbon::now()->year($year)->month($month)->startOfMonth(),
            'end'   => Carbon::now()->year($year)->month($month)->endOfMonth()
        ];
    }

    public function calculateDaysInMonth($year, $month)
    {
        $desiredMonth = Carbon::now()->year($year)->month($month)->startOfMonth();

        return $desiredMonth->copy()->diffInDays($desiredMonth->copy()->addMonth());
    }

    public function fetchAnalyticsData($client, Period $period)
    {
        $compareParams = 'ga:sessions, ga:users, ga:pageViews, ga:pageViewsPerSession, ga:avgSessionDuration, ga:bounceRate, ga:percentNewSessions';
        $data          = $client->performQuery($period, $compareParams)->totalsForAllResults;

        $analyticsData = $this->trimKeys($data)->all();

        return $analyticsData;
    }

    public function fetchDeviceCategories($client, $period)
    {
        $compareParams = 'ga:sessions';
        $dimensions    = ['dimensions' => 'ga:deviceCategory'];
        $data          = $client->performQuery($period, $compareParams, $dimensions)['rows'];

        $collection    = collect($data);

        $keyed         = $collection->mapWithKeys(function ($item) {
            return [$item[0] => $item[1]];
        });

        return $keyed;
    }

    public function fetchPaidSearchData($client, $period)
    {
        $compareParams = 'ga:sessions';
        $otherParams = [
            'dimensions' => 'ga:medium',
            'filters'    => 'ga:medium==cpc,ga:medium==Facebook Ads,ga:medium==Google Search,ga:medium==Google Search,ga:medium==Google Display,ga:medium==Google Video,ga:medium==Google Video,ga:medium==Facebook,ga:medium==Facebook ads',
            'sort'       => 'ga:sessions'
        ];

        $data = $this->trimKeys($client->performQuery($period, $compareParams, $otherParams)->totalsForAllResults)['sessions'];

        // echo '<pre>',print_r( $data ),'</pre>';
        // die();
        return $data;
    }

    public function fetchChannels($client, $period)
    {
        $compareParams = 'ga:sessions';
        $otherParams = [
            'dimensions' => 'ga:channelGrouping'
        ];

        $channelData = $client->performQuery($period, $compareParams, $otherParams)['rows'];

        $formattedArray = [];

        if(!is_array($channelData)){
            return false;
        }

        foreach ($channelData as $cd) {
            $formattedArray[strtolower(preg_replace('/\s/', '', $cd[0]))] = $cd[1];
        }

        return $formattedArray;
    }

    public function fetchTopPages($client, $period)
    {
        $compareParams = 'ga:pageviews';
        $otherParams = [
            'dimensions'  => 'ga:pagePath, ga:pageTitle',
            'sort'        => '-ga:pageViews',
            'filters'     => 'ga:pageTitle!=(not set)',
            'max-results' => 10,
        ];

        $pagesData = $client->performQuery($period, $compareParams, $otherParams);

        $topPages = $pagesData['rows'];
        $totalHits = $pagesData->totalsForAllResults['ga:pageviews'];

        return [$topPages, $totalHits]; // return [array of pages, total hits]
    }

    private function trimKeys($data)
    {
        $keys    = array_keys($data);
        $values  = array_values($data);
        $newKeys = preg_replace('/ga\:/', '', $keys);
        $newData = collect(array_combine($newKeys, $values));

        return $newData;
    }

    public static function determineName($name)
    {
        if (preg_match('/^\/results\.aspx\?/', $name)) {
            return 'Specific search results - Click to view';
        }

        if ($name == '/search.aspx?IsAdvanced=True') {
            return 'Advanced Search';
        }
        if ($name == '/') {
            return 'Home';
        }

        $newName = preg_replace('/-/', ' ', array_filter(explode('/', $name)));

        $readableName = preg_replace('/\?pg=/', 'Page ', $newName);
        $readableName = preg_replace('/\?id=/', '', $readableName);


        if (count($readableName) > 0) {
            $newName = implode(' > ', $readableName);
        }
        if (strlen($newName) > 100) {
            return 'Specific search results - Click to view';
        }


        return ucwords(strtolower($newName));
    }

    public function reportIsGood($reportData)
    {
        return $reportData->current_average_daily_sessions > 1 &&
                $reportData->current_users > 0;
    }
}
