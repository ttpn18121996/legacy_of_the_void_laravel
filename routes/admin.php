<?php

use App\Http\Controllers\Admin\ActressController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\VideoController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth'],
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    Route::get('/actresses', [ActressController::class, 'index'])->name('actresses.index');
    Route::get('/actresses/create', [ActressController::class, 'create'])->name('actresses.create');
    Route::post('/actresses', [ActressController::class, 'store'])->name('actresses.store');
    Route::get('/actresses/{id}/edit', [ActressController::class, 'edit'])->name('actresses.edit');
    Route::put('/actresses/{id}', [ActressController::class, 'update'])->name('actresses.update');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');

    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::put('/tags/{id}', [TagController::class, 'update'])->name('tags.update');

    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{id}/edit', [VideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{id}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{id}', [VideoController::class, 'destroy'])->name('videos.destroy');
});
