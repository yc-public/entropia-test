<?php

use Illuminate\Support\Facades\Route;

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
// 	return view('welcome');
// });

Route::get('/', 'MovieController@all');

Route::prefix('movies')->group(function() {
	Route::get('create', 'MovieController@create');
	Route::post('store', 'MovieController@store');

	Route::prefix('{id}')->group(function() {
		Route::get('view', 'MovieController@view');
		Route::get('edit', 'MovieController@edit');
		Route::put('update', 'MovieController@update');
		Route::delete('delete', 'MovieController@delete');
	});
});

Route::prefix('users')->group(function() {
	Route::post('store', 'UserController@store');
	Route::get('{category}', 'UserController@all');
});