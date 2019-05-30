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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('tasks', 'TaskController');

Route::get('/transfers', 'TransferController@index');
Route::patch('/transfers', 'TransferController@index');
Route::post('/transfers', 'TransferController@create');
Route::delete('/transfers', 'TransferController@destroy');

Route::post('/transfers/accept/{transfer}', 'TransferController@accept');
Route::delete('/transfers/reject/{transfer}', 'TransferController@reject');
