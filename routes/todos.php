<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('todos', [TodoController::class, 'index'])->name('todos.index');
    Route::post('todos', [TodoController::class, 'store'])->name('todos.store');
    Route::patch('todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
    Route::patch('todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
});
