<?php

use App\Livewire\Home;
use App\Livewire\ReviewMode;
use App\Livewire\TimedMode;
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

Route::get('/', Home::class);


Route::group(['prefix' => 'take-exam', 'as' => 'take-exam.'], function () {
    Route::get('review/{exam}', ['as' => 'review', 'uses' => ReviewMode::class]);
    Route::get('timed/{exam}', ['as' => 'timed', 'uses' => TimedMode::class]);
});
