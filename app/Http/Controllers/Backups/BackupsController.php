<?php

namespace App\Http\Controllers\Backups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Services\BackupServices;
use App\Models\Backups\Backup;

class BackupsController extends Controller
{
    public function index()
    {
        return Inertia::render('backups.index', [
            'model' => Backup::getStaticData(),
        ]);
    }

    public function store()
    {
        try{
            if(BackupServices::createBackup()){
                return redirect()->route('backups.index')->with([
                    'message' => [
                        'message' => 'Backup created successfully',
                        'type' => 'success'
                    ],
                ]);
            }
            return redirect()->route('backups.index')->with([
                'message' => [
                    'message' => 'Error creating backup',
                    'type' => 'error'
                ],
            ]);
        }
        catch(\Exception $e){
            return redirect()->route('backups.index')->with([
                'message' => [
                    'message' => 'Error creating backup',
                    'type' => 'error'
                ],
            ]);
        }
    }

    public function delete($name)
    {
        $backup = new Backup();
        if ($backup->delete($name)) {
            return redirect()->route('backups.index')->with([
                'message' => [
                    'message' => 'Backup deleted successfully',
                    'type' => 'success'
                ],
            ]);
        }
        
        return redirect()->route('backups.index')->with([
            'message' => [
                'message' => 'Error deleting backup',
                'type' => 'error'
            ],
        ]);
    }
}
