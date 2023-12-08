<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::middleware(['auth'])->group(function(){
    Route::prefix('letter')->as('letter.')->group(function(){
        Route::get('{id}', 'LetterController@show')->name('show');
        Route::delete('{id}', 'LetterController@remove')->name('delete');   
    });

    Route::prefix('outgoing')->as('outgoing.')->group(function(){
        Route::get('/', 'LetterController@outgoing')->name('list');
        Route::get('create', 'LetterController@create')->name('create');
        Route::post('store', 'LetterController@store')->name('store');
    });

    Route::get('/incoming', 'LetterController@incoming')->name('incoming');

    Route::prefix('classifications')->as('classifications.')->group(function(){
        Route::get('/', 'ClassificationsController@show')->name('list');
        Route::post('/', 'ClassificationsController@store')->name('create');
        Route::put('{id}', 'ClassificationsController@update')->name('update');
        Route::delete('{id}', 'ClassificationsController@remove')->name('delete');        
    });
    Route::prefix('users')->as('users.')->group(function(){
        Route::get('/', 'UserController@index')->name('index');
        Route::get('create', 'UserController@create')->name('create');
        Route::get('{id}', 'UserController@show')->name('show');
        Route::get('{user}/edit', 'UserController@edit')->name('edit');
        Route::post('/', 'UserController@store')->name('store');
        Route::put('{user}', 'UserController@update')->name('update');
        Route::delete('{user}', 'UserController@destroy')->name('destroy');
    });

    Route::post('/profile/update-photo', ['UserController@updateProfilePhoto'])->name('profile.update.photo');
});
