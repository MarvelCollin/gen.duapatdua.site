<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseSolveController;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\BpController;
use App\Http\Controllers\DailyTaskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\RundownController;
use App\Http\Controllers\TrainerController;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/getStorageLink', function () {
    Artisan::call('storage:link');
});

Route::middleware(['check.password'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

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
    Route::get('/bp/{id}', [BpController::class, 'show'])->name('bpprojects.show');
    Route::post('/bp', [BpController::class, 'store'])->name('bpprojects.store');
    Route::put('/bp/{id}', [BpController::class, 'update'])->name('bpprojects.update');
    Route::put('/bp/{id}/edit', [BpController::class, 'edit'])->name('bpprojects.edit');
    Route::delete('/bp/{id}', [BpController::class, 'destroy'])->name('bpprojects.destroy');

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');

    Route::get('/bpproject/{id}/details', [BpController::class, 'showDetails'])->name('bpproject.details');
    Route::put('/bpprojectTeams/{id}', [BpController::class, 'updateSubtitle'])->name('bpprojectTeams.update');

    Route::get('rundowns', [RundownController::class, 'index'])->name('rundowns.index');
    Route::put('/rundowns/{rundown}', [RundownController::class, 'update'])->name('rundowns.update');
    Route::post('rundowns', [RundownController::class, 'store'])->name('rundowns.store');
    Route::put('rundown-details/{rundownDetail}', [RundownController::class, 'update'])->name('rundown-details.update');
    Route::delete('rundown-details/{rundownDetail}', [RundownController::class, 'destroy'])->name('rundown-details.destroy');

    Route::resource('trainer', TrainerController::class);
    Route::get('/trainer-quiz', [TrainerController::class, 'quiz'])->name('quiz');

    Route::get('/trainees/acq', [TraineeController::class, 'showAcq'])->name('showAcq');
    Route::post('/trainees/{id}/edit-totalAcq', [TraineeController::class, 'editTotalAcq'])->name('editTotalAcq');

    Route::get('/permission', [PermissionController::class, 'show'])->name('showPermission');
    Route::post('/permission/create', [PermissionController::class, 'create'])->name('createPermission');
    Route::delete('/permission/delete/{id}', [PermissionController::class, 'remove'])->name('deletePermission');
    
    Route::get('/presentation', [PresentationController::class, 'show'])->name('showPresentation');
    Route::post('/presentation/create', [PresentationController::class, 'create'])->name('createPresentation');
    Route::delete('/presentation/delete/{id}', [PresentationController::class, 'delete'])->name('deletePresentation');

    Route::get('/daily-tasks', [DailyTaskController::class, 'show'])->name('showTasks');
});
