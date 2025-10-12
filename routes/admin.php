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
    Route::group([
        'prefix' => 'actresses',
        'as' => 'actresses.'
    ], function () {
        Route::get('/', [ActressController::class, 'index'])->name('index');
        Route::get('/create', [ActressController::class, 'create'])->name('create');
        Route::post('/', [ActressController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ActressController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ActressController::class, 'update'])->name('update');
        Route::patch('/{id}/update-thumbnail', [ActressController::class, 'updateThumbnail'])->name('update-thumbnail');
        Route::delete('/{id}', [ActressController::class, 'destroy'])->name('destroy');
    });

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');

    Route::group([
        'prefix' => 'tags',
        'as' => 'tags.'
    ], function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::post('/', [TagController::class, 'store'])->name('store');
        Route::put('/{id}', [TagController::class, 'update'])->name('update');
        Route::delete('/{id}', [TagController::class, 'destroy'])->name('destroy');
    });

    Route::group([
        'prefix' => 'videos',
        'as' => 'videos.'
    ], function () {
        Route::get('/', [VideoController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [VideoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VideoController::class, 'update'])->name('update');
        Route::delete('/{id}', [VideoController::class, 'destroy'])->name('destroy');
    });
});
