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
    Route::get('/', 'PageController@index');

    Route::get('/profile', 'ProfileController@profile');
    Route::get('/edit', 'ProfileController@edit')->name('edit');
    Route::get('/edit-email', 'ProfileController@editEmail')->name('editEmail');
    Route::get('/edit-password', 'ProfileController@editPassword')->name('editPassword');

    Route::put('/update', 'ProfileController@update')->name('update');
    Route::put('/update-email', 'ProfileController@updateEmail')->name('updateEmail');
    Route::put('/update-password', 'ProfileController@updatePassword')->name('updatePassword');

    Route::post('/update-photo', 'ProfileController@updatePhoto')->name('updatePhoto');
});
Auth::routes(['verify' => true]);
