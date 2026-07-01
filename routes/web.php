<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProjectMemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects with nested Tasks
    Route::resource('projects', ProjectController::class);
    Route::resource('projects.tasks', TaskController::class);
    Route::post('/projects/{project}/tasks/{task}/complete', [TaskController::class, 'complete'])->name('projects.tasks.complete');

    // Comment Routes
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Subtask Routes
    Route::post('/tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::patch('/subtasks/{subtask}/toggle', [SubtaskController::class, 'toggle'])->name('subtasks.toggle');
    Route::patch('/subtasks/{subtask}', [SubtaskController::class, 'update'])->name('subtasks.update');
    Route::delete('/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');

    // Category Routes
    Route::resource('categories', CategoryController::class);

    // Project Member Routes
    Route::get('/projects/{project}/members', [ProjectMemberController::class, 'index'])->name('projects.members');
    Route::get('/projects/{project}/members/add', [ProjectMemberController::class, 'create'])->name('projects.members.create');
    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])->name('projects.members.store');
    Route::patch('/members/{member}', [ProjectMemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}', [ProjectMemberController::class, 'destroy'])->name('members.destroy');
});

require __DIR__ . '/auth.php';