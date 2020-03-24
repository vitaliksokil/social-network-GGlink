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

Route::group(['middleware' => 'verified'], function () {
    // PageController ///////////////////////////////////////////////////////////////
    Route::get('/', 'PageController@index');

    ////////////////////////////////////////////////////////////////////////////////////

    // ProfileController ///////////////////////////////////////////////////////////////
    Route::get('/profile/id/{id}', 'ProfileController@profile')->name('profile');
    Route::get('/edit', 'ProfileController@edit')->name('edit');
    Route::put('/edit', 'ProfileController@update')->name('update');

    Route::prefix('settings')->group(function (){
        Route::get('/', 'ProfileController@settings')->name('settings');
        Route::put('/', 'ProfileController@updateSettings')->name('settings');
    });

    Route::prefix('email')->group(function (){
        Route::get('/edit', 'ProfileController@editEmail')->name('editEmail');
        Route::put('/edit', 'ProfileController@updateEmail')->name('updateEmail');
    });
    Route::prefix('password')->group(function (){
        Route::get('/edit', 'ProfileController@editPassword')->name('editPassword');
        Route::put('/edit', 'ProfileController@updatePassword')->name('updatePassword');
    });
    Route::post('/update-photo', 'ProfileController@updatePhoto')->name('updatePhoto');
    ////////////////////////////////////////////////////////////////////////////////////

    Route::resource('post','PostController');


});
Auth::routes(['verify' => true]);
