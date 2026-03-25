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

Route::get('/', function() {
    return redirect()->route('admin');
});

Route::get('home', function() {
    return redirect()->route('admin');
});

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
], function() {
    Route::group([
        'middleware' => 'guest:admin',
    ], function() {
        Route::get('login'                  , 'AuthController@login')->name('admin.login');
        Route::post('login'                 , 'AuthController@postLogin');

        Route::get('forgot-password'        , 'AuthController@forgot')->name('admin.forgot-password');
        Route::post('forgot-password'       , 'AuthController@postForgot');

        Route::get('reset-password/{token}' , 'AuthController@reset')->name('admin.reset-password');
        Route::post('reset-password/{token}', 'AuthController@postReset');

        Route::get('auth/google'            , 'SocialAuthController@authGoogle')->name('admin.auth-google');
        Route::get('auth/google/callback'   , 'SocialAuthController@authGoogleCallback');
    });

    Route::group([
        'middleware' => 'auth:admin',
    ], function() {
        Route::get('/'                      , 'HomeController@complaints')->name('admin');

        Route::get('complaints/{status}'    , 'HomeController@complaints')->name('admin.complaints');
        Route::post('complaint/update'      , 'HomeController@updateComplaint')->name('admin.update-complaint');

        Route::get('users'                  , 'HomeController@users')->name('admin.users');
        Route::get('users/like'             , 'HomeController@likeUsers')->name('admin.like-users');

        Route::get('profile'                , 'HomeController@profile')->name('admin.profile');
        Route::post('profile/email'         , 'HomeController@updateProfileEmail')->name('admin.update-profile-email');
        Route::post('profile/password'      , 'HomeController@updateProfilePassword')->name('admin.update-profile-password');

        Route::get('logout', function() {
            auth('admin')->logout();
            return redirect()->route('admin.login');
        })->name('admin.logout');
    });
});
