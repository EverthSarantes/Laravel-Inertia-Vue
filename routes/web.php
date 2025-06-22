<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Backups\BackupsController;
use App\Http\Controllers\Backups\SchedulesController;

use App\Http\Controllers\Api\SearchController;

use App\Http\Controllers\Exports\PrintController;


Route::middleware('HandleInertiaRequests')->group(function () {
    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('panel');
        }
        return Inertia::render('login');
    })->name('/');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('login', function () {
        return redirect()->route('/');
    });
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'CheckCanLogin'])->group(function () {
    Route::middleware('HandleInertiaRequests')->group(function () {

        Route::get('panel', [PanelController::class, 'index'])->name('panel');

        Route::prefix('users')->middleware('CheckRoles:users')->group(function () {
            Route::get('index', [UsersController::class, 'index'])->name('users.index');
            Route::get('show/{user}', [UsersController::class, 'show'])->name('users.show');
            Route::post('store', [UsersController::class, 'store'])->name('users.store');
            Route::put('update/{user}', [UsersController::class, 'update'])->name('users.update');
            Route::delete('delete/{user}', [UsersController::class, 'delete'])->name('users.delete');
            Route::post('addModule', [UsersController::class, 'addModule'])->name('users.addModule');
            Route::delete('deleteModule/{userModule}/{user}', [UsersController::class, 'deleteModule'])->name('users.deleteModule');
        });

        Route::prefix('backups')->middleware('CheckRoles:backups')->group(function () {
            Route::get('index', [BackupsController::class, 'index'])->name('backups.index');
            Route::post('store', [BackupsController::class, 'store'])->name('backups.store');
            Route::delete('delete/{name}', [BackupsController::class, 'delete'])->name('backups.delete');
            Route::get('download/{name}', [BackupsController::class, 'download'])->name('backups.download');

            Route::prefix('schedules')->group(function () {
                Route::put('update', [SchedulesController::class, 'update'])->name('backups.schedules.update');
            });
        });
        
    });

    Route::prefix('api')->group(function () {
        Route::get('search/{model}', [SearchController::class, 'search']);
        Route::get('select/{model}/{search}', [SearchController::class, 'searchSelect']);
    });

    Route::prefix('exports')->group(function () {
        Route::get('print', [PrintController::class, 'print'])->name('exports.print');
    });
});