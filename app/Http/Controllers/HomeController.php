<?php

namespace App\Http\Controllers;

use App\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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

        return view('home', compact('companies', 'dates'));
    }
}
