<?php

use App\Http\Controllers\ActressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GlobalSeachController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::view('/login', 'login')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/videos/stream', [VideoController::class, 'stream'])->name('videos.stream');

Route::group([
    'middleware' => ['auth'],
], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [HomeController::class, 'index'])->name('home');

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

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/approved', [ReviewController::class, 'approved'])->name('reviews.approved');
    Route::get('/watch', [ReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/publish-video', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reject', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/options/actresses', [OptionController::class, 'getActresses'])->name('options.get-actresses');
    Route::get('/options/tags', [OptionController::class, 'getTags'])->name('options.get-tags');

    Route::get('/global-search', GlobalSeachController::class)->name('global-search');

    Route::view('/blank', 'blank')->name('blank');
});

require __DIR__.'/admin.php';
