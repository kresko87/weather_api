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
Auth::routes();

Route::get('login-provider/{provider}', 'Auth\LoginController@redirectToProvider')->name('loginWithProvider');
Route::get('login-callback/{provider}', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/', 'HomeController@index')->name('home')->middleware('auth');


Route::post('/allPlaces', 'PlacesController@getAllCitiesFromUser')->middleware('auth');
Route::get('/addPlace', 'PlacesController@getCityByNameAndAddItToUser')->middleware('auth');
Route::post('/removePlaceAndReturnAllCitiesFromUser', 'PlacesController@removePlaceAndReturnAllCitiesFromUser')->middleware('auth');


//Route::get('/temp','TemperatureController@saveTemperatureForAllCities')->middleware('auth');
Route::post('/getTemperatures', 'TemperatureController@getTemperaturesForParameters')->middleware('auth');
