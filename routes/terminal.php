<?php

use App\Http\Controllers\Terminal\CommandController;
use App\Http\Middleware\ExitTerminal;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth', ExitTerminal::class],
    'prefix' => 'terminal',
    'as' => 'terminal.'
], function () {
    Route::get('/', function () {
        return view('terminal');
    })->name('main')->middleware(['auth']);

    Route::post('/execute-command', CommandController::class)->name('execute-command');

    Route::post('/terminate', function () {
        session()->forget('is_terminal');
        return response()->json([
            'success' => true,
            'redirect_to' => route('home'),
        ]);
    })->name('terminate');
});
