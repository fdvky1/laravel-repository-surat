<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login'); //view('welcome');
});

Auth::routes([
    'register' => false
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::middleware(['auth'])->group(function(){
    Route::prefix('letter')->as('letter.')->group(function(){
        Route::post('store', 'LetterController@store')->name('store');
        Route::post('status', 'LetterController@updateStatus')->name('status');
        Route::get('{id}', 'LetterController@show')->name('show');
        Route::delete('{id}', 'LetterController@remove')->name('delete');
        Route::put('{id}', 'LetterController@update')->name('update');
    });

    Route::prefix('outgoing')->as('outgoing.')->group(function(){
        Route::get('/', 'LetterController@outgoing')->name('list');
        Route::get('print/{id}', 'LetterController@print')->name('print');
        Route::get('create', 'LetterController@createOutgoing')->name('create');
        // Route::post('store', 'LetterController@store')->name('store');
        Route::get('update/{id}', 'LetterController@editOutgoing')->name('update');
        // Route::put('{id}', 'LetterController@update')->name('update');
    });

    Route::prefix('incoming')->as('incoming.')->group(function(){
        Route::get('/', 'LetterController@incoming')->name('list');
        Route::get('create', 'LetterController@createIncoming')->name('create');
        Route::get('update/{id}', 'LetterController@updateIncoming')->name('update');
    });

    Route::prefix('dispositions')->as('dispositions.')->group(function(){
        Route::get('/', 'DispositionController@index')->name('list');
        Route::get('{id}', 'DispositionController@show')->name('show');
        Route::put('update/{id}', 'DispositionController@update')->name('update');
    });


    Route::prefix('classifications')->middleware(['role:admin,superadmin'])->as('classifications.')->group(function(){
        Route::get('/', 'ClassificationsController@show')->name('list');
        Route::post('/', 'ClassificationsController@store')->name('create');
        Route::put('{id}', 'ClassificationsController@update')->name('update');
        Route::delete('{id}', 'ClassificationsController@remove')->name('delete');
    });

    Route::prefix('users')->middleware(['role:admin,superadmin'])->as('users.')->group(function(){
        Route::get('/', 'UserController@index')->name('index');
        Route::get('{id}', 'UserController@show')->name('show');
        Route::post('/', 'UserController@store')->name('store');
        Route::put('{user}', 'UserController@update')->name('update');
        Route::delete('{user}', 'UserController@destroy')->name('destroy');
    });

    Route::prefix('setting')->middleware(['role:admin,superadmin'])->as('setting.')->group(function(){
        Route::get('/', 'SettingController@show')->name('show');
        Route::put('/', 'SettingController@update')->name('update');
        Route::post('update-photo', 'SettingController@updatePhoto')->name('update_photo');
    });

    Route::post('/profile/update-photo', 'ProfileController@updateProfilePhoto')->name('profile.update.photo');
});
