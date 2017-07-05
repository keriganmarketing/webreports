<?php


Route::get('/', 'HomeController@index');
Route::any('/{company}/report/{year}/{month}', 'AnalyticsController@checkDatabase');
Route::get('/master-report/{year}/{month}', 'AnalyticsController@masterReport');
Route::get('/company/create', 'CompaniesController@create')->name('company.create');
Route::post('/company', 'CompaniesController@store');

Route::get('/sem-report', 'SEMReportController@create')->name('semreport.create');
Route::any('/{company}/semreport/{year}/{month}', 'SEMReportController@checkDatabase')->name('sem.runReport');




Auth::routes();

Route::get('/home', 'HomeController@index');
