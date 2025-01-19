<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
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
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/get', [TaskController::class, 'get']);
    Route::get('/task/get/{id}', [TaskController::class, 'getById']);
    Route::post('/tasks/add', [TaskController::class, 'store']);
    Route::put('/tasks/update/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/remove/{id}', [TaskController::class, 'destroy']);
});

Route::middleware('auth.basic.custom')->group(function () {
    Route::get('/api/tasks/get', [TaskController::class, 'get']);
    Route::get('/api/task/get/{id}', [TaskController::class, 'getById']);
    Route::post('/api/tasks/add', [TaskController::class, 'store']);
    Route::put('/api/tasks/update/{id}', [TaskController::class, 'update']);
    Route::delete('/api/tasks/remove/{id}', [TaskController::class, 'destroy']);
});

require __DIR__.'/auth.php';
