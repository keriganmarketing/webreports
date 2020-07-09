<?php

// Web Report
Route::get('/', 'HomeController@index');
Route::any('/{company}/report/{year}/{month}', 'ReportController@index');
// Route::get('/master-report/{year}/{month}', 'AnalyticsController@masterReport');

// Company Control
Route::get('/company/create', 'CompaniesController@create')->name('company.create');
Route::post('/company', 'CompaniesController@store');

// SEM Report
// Route::get('/sem-report', 'SEMReportController@create')->name('semreport.create');
// Route::any('/{company}/semreport/{year}/{month}', 'SEMReportController@show');

// Trend Data
Route::any('/api/v1/trend/{company}/{from}/{to}','TrendController@data');
Route::any('/api/v1/build/{company}/{from}/{to}','TrendController@build');

Auth::routes();

Route::get('/home', 'HomeController@index');
