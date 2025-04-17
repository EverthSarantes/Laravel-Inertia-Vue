<?php

use App\Http\Middleware\CheckRoles;
use App\Http\Middleware\NoCache;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LogUserActions;
use Illuminate\Http\Request;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\Backups\ScheduledBackup;
use App\Http\Services\BackupServices;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'CheckRoles' => CheckRoles::class,
            'NoCache' => NoCache::class,
            'HandleInertiaRequests' => HandleInertiaRequests::class,
        ]);
        $middleware->append([LogUserActions::class]);
        $middleware->redirectGuestsTo(fn (Request $request) => route('/'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        ScheduledBackup::where('active', true)->get()->each(function ($backup) use ($schedule) {
            foreach ($backup->times as $time) {
                $schedule->call(fn () => BackupServices::createBackup())
                    ->days($backup->days)
                    ->at($time)
                    ->name("Backup-{$backup->id}-{$time}")
                    ->evenInMaintenanceMode()
                    ->after(function (Stringable $output) {
                        Log::channel('backups')->error('Backup failed', [
                            'output' => $output,
                        ]);
                    });
            }
        });
    })
    ->create();
