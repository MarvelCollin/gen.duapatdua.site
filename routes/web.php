<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseSolveController;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;

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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['check.password'])->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/forum', [ForumController::class, 'show'])->name('showForum');
    Route::post('/forum/store', [ForumController::class, 'store'])->name('storeForum');
    Route::patch('/forums/{id}/update-status', [ForumController::class, 'updateStatus'])->name('updateForumStatus');
    Route::post('/forums/shuffle', [ForumController::class, 'shuffle'])->name('shuffleForums');
    Route::patch('/forums/{id}/update-link', [ForumController::class, 'updateLink'])->name('updateForumLink');
    Route::delete('/forums/{id}', [ForumController::class, 'deleteForum'])->name('deleteForum');

    Route::get('/casesolve', [CaseSolveController::class, 'index'])->name('casesolve.index');
    Route::get('/casesolve/create', [CaseSolveController::class, 'create'])->name('casesolve.create');
    Route::post('/casesolve', [CaseSolveController::class, 'store'])->name('casesolve.store');
    Route::put('/casesolve/{id}/edit', [CaseSolveController::class, 'edit'])->name('casesolve.edit');
    Route::put('/casesolve/{id}/update', [CaseSolveController::class, 'update'])->name('casesolve.update');
    Route::get('/casesolve/{id}', [CaseSolveController::class, 'show'])->name('casesolve.show');

    Route::resource('trainee', TraineeController::class);
    Route::get('/trainee-quiz', [QuizController::class, 'showTraineeQuiz'])->name('showTraineeQuiz');
});
