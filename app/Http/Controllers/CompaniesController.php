<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
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
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Company::create([
            'name' => $request->name,
            'viewId' => $request->viewId,
            'url' => $request->url
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $companies = Company::where(['active' => true])->orderby('name')->get();
        $disabledCompanies = Company::where(['active' => false])->orderby('name')->get();
        return view('companies.show', compact('companies','disabledCompanies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $company->name = $request->name;
        $company->viewId = $request->viewId;
        $company->url = $request->url;
        $company->save();

        return back();
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Company  $company
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request, Company $company)
    {
        $companyDisabled = $company;
        $company->active = 0;
        $company->save();

        return redirect('companies')->with('status', $companyDisabled->name . ' has been disabled.');
    }

    /**
     * Disable the specified resource in storage.
     *
     * @param  Company  $company
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request, Company $company)
    {
        $companyEnabled = $company;
        $company->active = 1;
        $company->save();

        return redirect('companies')->with('status', $companyEnabled->name . ' has been enabled.');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  Company  $company
     * @param  Request  $request
     * @return Response
     */
    public function delete(Request $request, Company $company)
    {
        $companyDeleted = $company;
        $company->delete();

        return redirect('companies')->with('status', $companyDeleted->name . ' has been deleted.');
    }
}
