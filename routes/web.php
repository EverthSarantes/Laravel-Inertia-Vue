<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PanelController;

use App\Http\Controllers\AdministrationApp\PanelController as AdministrationAppPanelController;

use App\Http\Controllers\AdministrationApp\Users\UsersController;
use App\Http\Controllers\AdministrationApp\Users\UserTemplateController;
use App\Http\Controllers\Profile\ProfilesController;
use App\Http\Controllers\Profile\SocialAuthController;

use App\Http\Controllers\AdministrationApp\Backups\BackupsController;
use App\Http\Controllers\AdministrationApp\Backups\SchedulesController;

use App\Http\Controllers\AdministrationApp\Config\ConfigController;
use App\Http\Controllers\AdministrationApp\Reports\ReportsController;
use App\Http\Controllers\AdministrationApp\Config\LogsController;

use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ModelFilters\ModelFiltersController;

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

    Route::prefix('socialAuth')->group(function () {
        Route::get('redirect/{provider}/{state?}', [SocialAuthController::class, 'redirect'])->name('socialAuth.redirect');
        Route::get('callback/{provider}', [SocialAuthController::class, 'callback'])->name('socialAuth.callback');
        Route::delete('removeProvider/{userProvider}', [SocialAuthController::class, 'removeProvider'])->name('profile.removeProvider');
    });
});

Route::middleware(['auth', 'CheckCanLogin'])->group(function () {
    Route::middleware('HandleInertiaRequests')->group(function () {

        Route::get('panel', [PanelController::class, 'index'])->name('panel');

        Route::get('administration/panel', [AdministrationAppPanelController::class, 'index'])->name('administration_app.index');

        Route::prefix('profile')->group(function () {
            Route::get('index', [ProfilesController::class, 'index'])->name('profile.index');
            Route::put('update', [ProfilesController::class, 'update'])->name('profile.update');
            Route::put('changePassword', [ProfilesController::class, 'changePassword'])->name('profile.changePassword');
        });

        Route::prefix('users')->middleware('CheckRoles:users')->group(function () {
            Route::get('index', [UsersController::class, 'index'])->name('users.index');
            Route::get('show/{user}', [UsersController::class, 'show'])->name('users.show');
            Route::post('store', [UsersController::class, 'store'])->name('users.store');
            Route::put('update/{user}', [UsersController::class, 'update'])->name('users.update');
            Route::delete('delete/{user}', [UsersController::class, 'delete'])->name('users.delete');

            Route::post('addModule', [UsersController::class, 'addModule'])->name('users.addModule');
            Route::delete('deleteModule/{userModule}/{user}', [UsersController::class, 'deleteModule'])->name('users.deleteModule');

            Route::post('addUserModelFilter/{user}', [UsersController::class, 'addUserModelFilter'])->name('users.addUserModelFilter');
            Route::delete('removeUserModelFilter/{userModelFilter}/{user}', [UsersController::class, 'removeUserModelFilter'])->name('users.removeUserModelFilter');

            Route::prefix('templates')->group(function () {
                Route::get('index', [UserTemplateController::class, 'index'])->name('users.templates.index');
                Route::get('show/{userTemplate}', [UserTemplateController::class, 'show'])->name('users.templates.show');
                Route::post('store', [UserTemplateController::class, 'store'])->name('users.templates.store');
                Route::put('update/{userTemplate}', [UserTemplateController::class, 'update'])->name('users.templates.update');
                Route::delete('delete/{userTemplate}', [UserTemplateController::class, 'delete'])->name('users.templates.delete');

                Route::post('addModule', [UserTemplateController::class, 'addModule'])->name('users.templates.addModule');
                Route::delete('deleteModule/{userTemplateModule}/{userTemplate}', [UserTemplateController::class, 'deleteModule'])->name('users.templates.deleteModule');

                Route::post('addUserTemplateModelFilter/{userTemplate}', [UserTemplateController::class, 'addUserTemplateModelFilter'])->name('users.templates.addUserTemplateModelFilter');
                Route::delete('removeUserTemplateModelFilter/{userTemplateModelFilter}/{userTemplate}', [UserTemplateController::class, 'removeUserTemplateModelFilter'])->name('users.templates.removeUserTemplateModelFilter');
            });
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

        Route::prefix('reports')->middleware('CheckRoles:reports')->group(function () {
            Route::get('index', [ReportsController::class, 'index'])->name('reports.index');
        });

        Route::prefix('configurations')->middleware('CheckRoles:config')->group(function () {
            Route::get('index', [ConfigController::class, 'index'])->name('config.index');
            Route::put('update/{configuration}', [ConfigController::class, 'update'])->name('config.update');
        });

        Route::prefix('logs')->middleware('CheckRoles:config')->group(function () {
            Route::get('index', [LogsController::class, 'index'])->name('logs.index');
            Route::delete('cleanUserLogs', [LogsController::class, 'cleanUserLogs'])->name('logs.cleanUserLogs');
        });
    });

    Route::prefix('api')->group(function () {
        Route::get('search/{model}', [SearchController::class, 'search']);
        Route::get('select/{model}/{search}', [SearchController::class, 'searchSelect']);

        Route::prefix('modelFilters')->middleware('CheckRoles:users')->group(function () {
            Route::get('getAvailableFilterByModel/{model}', [ModelFiltersController::class, 'getAvailableFilterByModel'])->name('api.modelFilters.getAvailableFilterByModel');
        });
    });

    Route::prefix('exports')->group(function () {
        Route::get('print', [PrintController::class, 'print'])->name('exports.print');
    });
});