<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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
    Route::get('/letter/{id}', 'LetterController@show')->name('letter.show');
    Route::delete('/letter/{id}', 'LetterController@remove')->name('letter.delete');
    Route::get('/outgoing', 'LetterController@outgoing')->name('outgoing');
    Route::get('/outgoing/create', 'LetterController@create')->name('outgoing.create');
    Route::post('/outgoing/store', 'LetterController@store')->name('incoming.store');
    Route::get('/incoming', 'LetterController@incoming')->name('incoming');
    Route::get('/classifications', 'ClassificationsController@show')->name('classifications');
    Route::post('/classifications', 'ClassificationsController@store')->name('classifications.create');
    Route::put('/classifications/{id}', 'ClassificationsController@update')->name('classifications.update');
    Route::delete('/classifications/{id}', 'ClassificationsController@remove')->name('classifications.delete');
});

Route::post('/profile/update-photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.update.photo');
