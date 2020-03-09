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

Route::get('/', function () {
    $site_title = App\Settings::all()->first()->site_title;
    $site_subtitle = App\Settings::all()->first()->site_subtitle;
    return view('index', compact('site_title', 'site_subtitle'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
