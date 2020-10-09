<?php

namespace App\Http\Controllers;

use Analytics;
use App\Report;
use App\Company;
use App\Trend;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class TrendController extends Controller
{

    public function data(Company $company, $from, $to)
    {        
        $trend = Trend::where([
            ['company_id', '=', $company->id],
            ['date', '>=', $from],
            ['date', '<=', $to]
        ])->orderBy('date')->get()->toArray();

        return response()->json($trend);
    }

    public function build(Company $company, $from, $to)
    {
        return response()->json((new Trend())->runReport($company, $from, $to));
    }

    public function buildAll($from, $to)
    {
        $companies = Company::where([
            ['viewId', '!=', '']
        ])->orderBy('id')->get();

        // dd($companies);

        foreach($companies as $company){
            echo $this->build($company, $from, $to);
        }
    }

    public function cron()
    {
        $companies = Company::where(['active' => true])->orderby('name')->get();
        $from = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $to = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        foreach($companies as $company){
            (new Trend())->runReport($company, $from, $to);
        }
    }
}