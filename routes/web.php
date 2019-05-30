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

Route::get('/tasks', 'TaskController@index');
Route::post('/tasks', 'TaskController@store');
Route::patch('/tasks/{task}', 'TaskController@archive');

Route::get('/transfers', 'TransferController@index');
Route::post('/transfers', 'TransferController@create');
Route::delete('/transfers/{transfer}', 'TransferController@destroy');

Route::patch('/transfers/accept/{transfer}', 'TransferController@accept');
Route::patch('/transfers/reject/{transfer}', 'TransferController@reject');

Route::redirect('/', '/tasks');
Route::redirect('/home', '/tasks');
