<?php
use ;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::post('/','HomeController@magic');

Route::get('db_update',function(){
    $api = new \App\Http\RiotApi();
    $api->getChampionsData('eune');
    return "done";
})
