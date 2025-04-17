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
                        'message' => 'Respaldo creado correctamente',
                        'type' => 'success'
                    ],
                ]);
            }
            return redirect()->route('backups.index')->with([
                'message' => [
                    'message' => 'Error al crear el respaldo',
                    'type' => 'error'
                ],
            ]);
        }
        catch(\Exception $e){
            return redirect()->route('backups.index')->with([
                'message' => [
                    'message' => 'Error al crear el respaldo',
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
                    'message' => 'Respaldo eliminado correctamente',
                    'type' => 'success'
                ],
            ]);
        }

        return redirect()->route('backups.index')->with([
            'message' => [
                'message' => 'Error al eliminar el respaldo',
                'type' => 'error'
            ],
        ]);
    }

    public function download($name)
    {
        $backup = new Backup();
        $path = $backup->download($name);
        
        return response()->download($path)->deleteFileAfterSend(true);
    }
}
