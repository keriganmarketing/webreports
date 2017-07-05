<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::any('/{company}/report/{year}/{month}', 'AnalyticsController@checkDatabase');
Route::get('/master-report/{year}/{month}', 'AnalyticsController@masterReport');
Route::get('/company/create', 'CompaniesController@create')->name('company.create');
Route::post('/company', 'CompaniesController@store');
Route::get('/sem-report', 'SEMReportController@create')->name('semreport.create');




Auth::routes();

Route::get('/home', 'HomeController@index');
