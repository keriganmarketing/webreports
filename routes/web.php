<?php

// Web Report
Route::get('/', 'HomeController@index')->name('home');
Route::any('/{company}/report/{year}/{month}', 'ReportController@index')->name('report');
// Route::get('/master-report/{year}/{month}', 'AnalyticsController@masterReport');

// SEM Report
// Route::get('/sem-report', 'SEMReportController@create')->name('semreport.create');
// Route::any('/{company}/semreport/{year}/{month}', 'SEMReportController@show');

// Trend Data
Route::any('/api/v1/trend/{company}/{from}/{to}','TrendController@data');

Auth::routes();

// Trend Utilities
Route::any('/api/v1/build/{company}/{from}/{to}','TrendController@build');
Route::any('/api/v1/buildall/{from}/{to}','TrendController@buildAll');

Route::get('/settings', 'AdminController@settings')->name('admin.settings');

// Users
Route::get('/users', 'UsersController@show')->name('users.show');
Route::get('/user/{user}/edit', 'UsersController@edit')->name('users.edit');
Route::post('/user/{user}/edit', 'UsersController@update');

// Company Control
Route::get('/company/create', 'CompaniesController@create')->name('company.create');
Route::get('/company/{company}/edit', 'CompaniesController@edit')->name('company.edit');
Route::get('/companies', 'CompaniesController@show')->name('company.show');
Route::post('/company', 'CompaniesController@store');
Route::post('/company/{company}/edit', 'CompaniesController@update');