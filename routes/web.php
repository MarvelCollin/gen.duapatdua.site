<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseSolveController;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\BpController;
use App\Http\Controllers\TeamController;


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

    Route::get('/bp', [BpController::class, 'index'])->name('bpprojects.index');
    Route::get('/bpprojects/{id}', [BpController::class, 'show'])->name('bpprojects.show');
    Route::post('/bpprojects', [BpController::class, 'store'])->name('bpprojects.store');
    Route::put('/bpprojects/{id}', [BpController::class, 'update'])->name('bpprojects.update');
    Route::put('/bpprojects/{id}/edit', [BpController::class, 'edit'])->name('bpprojects.edit');
    Route::delete('/bpprojects/{id}', [BpController::class, 'destroy'])->name('bpprojects.destroy');
    
    Route::get('/bpprojectTeams', [TeamController::class, 'index'])->name('bpprojectTeams.index');
    Route::get('/bpprojectTeams/{bpprojectTeam}/edit', [TeamController::class, 'edit'])->name('bpprojectTeams.edit');
    Route::put('/bpprojectTeams/{bpprojectTeam}', [TeamController::class, 'update'])->name('bpprojectTeams.update');

    Route::put('/bpprojectteams/{id}', [TeamController::class, 'updateBpprojectTeam'])->name('bpprojectteams.update');
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/{id}', [TeamController::class, 'show'])->name('teams.show');
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
});
