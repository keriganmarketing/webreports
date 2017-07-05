<?php

namespace App\Http\Controllers;

use Analytics;
use App\Report;
use App\Company;
use App\SEMReport;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;

class SEMReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $companies = Company::all()->sortBy('name');
        return view('semreports.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SEMReport  $sEMReport
     * @return \Illuminate\Http\Response
     */
    public function show(SEMReport $SEMReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SEMReport  $sEMReport
     * @return \Illuminate\Http\Response
     */
    public function edit(SEMReport $SEMReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SEMReport  $sEMReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SEMReport $SEMReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SEMReport  $sEMReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(SEMReport $SEMReport)
    {
        //
    }

    public function checkDatabase(Company $company, $year, $month)
    {
       $semReport = SEMReport::where([
           ['company_id', '=', $company->id],
           ['year', '=', $year],
           ['month', '=', $month]
       ])->first();

      if( ! $semReport){
          return $this->runReport($company, $year, $month);
      }

      return view('reports.show', compact('semReport', 'company'));
    }

    private function runReport(Company $company, $year, $month)
    {
        $client                = Analytics::setViewId($company->viewId);
        $report                = new Report();
        $semreport             = new SEMReport();
        //determine our dates
        $currentDates  = $report->determineDates($year, $month);
        //How many days in the query?
        $daysInMonth = $report->calculateDaysInMonth($year, $month);

        //create date range for query
        $currentPeriod  = Period::create($currentDates['start'], $currentDates['end']);

        $current  = $semreport->testMethod($client, $currentPeriod);

        dd($current);

    }
}
