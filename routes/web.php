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

// Route::get('/', function () { 
//     return view('offline_report.offline_report');
// });

  
Auth::routes(); 
 
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
//Route::group(['middleware' => ['auth']],function(){
Route::match(['get','post'],'/','Offline_reportController@index');
Route::match(['get','post'],'/login','Offline_reportController@login');
Route::match(['get','post'],'/logout','Offline_reportController@logout');
Route::match(['get','post'],'/reports','Offline_reportController@view_offline_report')->name('offline_report.offline_report');

Route::match(['get','post'],'/download/{hashPath}','Offline_reportController@fileDownload');
Route::match(['get','post'],'/session','HomeController@session');
//});  