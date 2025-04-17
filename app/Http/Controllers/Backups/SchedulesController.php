<?php

namespace App\Http\Controllers\Backups;

use App\Http\Controllers\Controller;
use App\http\Requests\BaseFormRequest;
use App\Models\Backups\ScheduledBackup;

class SchedulesController extends Controller
{
    public function update(BaseFormRequest $request)
    {
        $request->validate([
            'days' => 'nullable|array',
            'days.*' => 'nullable|integer|between:0,6',
            'hours' => 'nullable|array',
            'hours.*' => ['nullable', 'regex:/^\d{2}:\d{2}$/'],
        ], [
            'days.array' => 'El campo de días debe ser un arreglo.',
            'days.*.integer' => 'El campo de días debe ser un número entero.',
            'days.*.between' => 'El campo de días debe ser un día de la semana válido (0-6).',
            'hours.array' => 'El campo de horas debe ser un arreglo.',
            'hours.*.regex' => 'El campo de horas debe tener el formato HH:MM.',
        ]);

        try{
            $backup = ScheduledBackup::firstOrNew(['id' => 1]);

            $backup->days = array_map('intval', $request->days);

            $backup->times = $request->hours;

            $backup->active = $request->active ? true : false;
            $backup->save();

            return back()->with([
                'message' => [
                    'message' => 'La tarea de respaldo fue actualizada correctamente.',
                    'type' => 'success',
                ],
            ]);

        } catch(\Exception $e) {
            return back()->with([
                'message' => [
                    'message' => 'Error al programar el respaldo.',
                    'type' => 'danger'
                ],
            ]);
        }
    }
}
