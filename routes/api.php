<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'namespace' => 'API',
], function() {
    Route::get('complaints'     , 'ApiController@complaints');
    Route::post('auth-google'   , 'ApiController@authGoogle');
    Route::post('like'          , 'ApiController@like');
    Route::post('report-fraud'  , 'ApiController@reportFraud');
});
