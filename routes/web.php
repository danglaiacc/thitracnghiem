<?php

use App\Livewire\Exam\Upsert as ExamUpsert;
use App\Livewire\Exam\Index as ExamIndex;
use App\Livewire\Home;
use App\Livewire\QueryQuestion;
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

Route::group(['prefix' => 'exam', 'as' => 'exam.'], function () {
    Route::get('/', ['as' => 'index', 'uses' => ExamIndex::class]);
    Route::get('create', ['as' => 'create', 'uses' => ExamUpsert::class]);
    Route::get('/{exam}', ['as' => 'upsert', 'uses' => ExamUpsert::class]);
});

Route::group(['prefix' => 'query-question', 'as' => 'query-question.'], function () {
    Route::get('/', ['as' => 'index', 'uses' => QueryQuestion::class]);
});
