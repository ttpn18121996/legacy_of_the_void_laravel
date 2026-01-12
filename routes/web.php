<?php

use App\Http\Controllers\ActressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GlobalSeachController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListViewController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\RandomVideoController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::view('/login', 'login')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/videos/stream', [VideoController::class, 'streamingViaNginx'])->name('videos.stream');

Route::group([
    'middleware' => ['auth'],
], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/search', [HomeController::class, 'search'])->name('search');

    Route::get('/actresses', [ActressController::class, 'index'])->name('actresses.index');
    Route::post('/actresses/update-tags', [ActressController::class, 'updateTags'])->name('actresses.update-tags');
    Route::get('/actresses/{id}', [ActressController::class, 'show'])->name('actresses.show');

    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::post('/videos/update-tags', [VideoController::class, 'updateTags'])->name('videos.update-tags');
    Route::post('/videos/update-actresses', [VideoController::class, 'updateActresses'])->name('videos.update-actresses');
    Route::post('/videos/update-categories', [VideoController::class, 'updateCategories'])->name('videos.update-categories');
    Route::post('/videos/increment-like', [VideoController::class, 'incrementLike'])->name('videos.increment-like');
    Route::get('/videos/{id}', [VideoController::class, 'show'])->name('videos.show');

    Route::group([
        'as' => 'list-view.',
        'prefix' => 'list-view',
    ], function () {
        Route::get('/', [ListViewController::class, 'index'])->name('index');
        Route::get('/watch', [ListViewController::class, 'show'])->name('show');
        Route::post('/', [ListViewController::class, 'store'])->name('store');
        Route::put('/publish-video', [ListViewController::class, 'update'])->name('update');
        Route::delete('/reject', [ListViewController::class, 'destroy'])->name('destroy');
    });

    Route::get('/options/actresses', [OptionController::class, 'getActresses'])->name('options.get-actresses');
    Route::get('/options/tags', [OptionController::class, 'getTags'])->name('options.get-tags');

    Route::get('/global-search', GlobalSeachController::class)->name('global-search');

    Route::get('/random-videos', RandomVideoController::class)->name('random-videos');
    Route::view('/blank', 'blank')->name('blank');
});

require __DIR__.'/admin.php';
