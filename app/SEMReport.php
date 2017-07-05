<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SEMReport extends Model
{
    protected $guarded = [];
    protected $table = 'semreports';

    public function testMethod($client, $period)
    {
        $compareParams = 'ga:adClicks';
        $otherParams = [
            'dimensions' => 'ga:adGroup',
            // 'filters' => '',
            // 'sort' => '-ga:CTR'
        ];

        $data = $client->performQuery($period, $compareParams, $otherParams);

        return $data;
        
    }
}
