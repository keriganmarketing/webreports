<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function settings()
    {
        $companies = Company::where(['active' => true])->orderby('name')->get();

        return view('admin.settings', compact('companies'));
    }
}
