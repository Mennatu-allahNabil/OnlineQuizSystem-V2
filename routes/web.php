<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::resource('topics', TopicController::class);

Route::get('/admin', [DashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('auth');

Route::post('users/search', [AdminController::class, 'SearchForUser'])
    ->name('users.search');

Route::get('admins/showall', [AdminController::class, 'alladmins'])
    ->name('admins.showall')->middleware('admin');

Route::get('users', [AdminController::class, 'getusers'])
    ->name('users.showall')->middleware('admin');

Route::get('users/showall', [AdminController::class, 'allusers'])
    ->name('allusers.showall')->middleware('admin');

Route::resource('admins', AdminController::class)->middleware('admin');
Route::resource('admins', AdminController::class)->only(['create', 'store'])->middleware('super_admin');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

<<<<<<< HEAD
require __DIR__ . '/auth.php';
=======

Route::get('/topics/{topic}', [QuizController::class, 'showQuizzesByTopic'])->name('quizzes.by_topic');
Route::get('/quiz/{quiz}', [QuizController::class, 'showQuiz'])->name('quiz.show');

require __DIR__.'/auth.php';
>>>>>>> 7b35f109265b15cc8a09a6843ee8ff90e5dc92d5
