<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseFormRequest;
use Inertia\Inertia;
use App\Models\Users\Templates\UserTemplate;

class UserTemplateController extends Controller
{
    /**
     * Display a paginated list of users templates.
     *
     * @param int $pagination
     * @return \Inertia\Response
     */
    public function index($pagination = 20)
    {
        return Inertia::render('users_templates.index', [
            'model' => UserTemplate::getStaticData(),
        ]);
    }

    /**
     * Display details of a specific user template.
     *
     * @param \App\Models\Users\Templates\UserTemplate $userTemplate
     * @return \Inertia\Response
     */
    public function show(UserTemplate $userTemplate)
    {
        return true;
    }

    /**
     * Store a new user template in the database.
     *
     * @param \App\Http\Requests\BaseFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BaseFormRequest $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'description.max' => 'La descripción no puede exceder los 1000 caracteres.',
        ]);
        

        $userTemplate = UserTemplate::create($request->only(['name', 'description']));

        if($userTemplate) {
            return redirect()->route('users.templates.show', ['userTemplate' => $userTemplate])->with([
                'message' => [
                    'message' => 'Plantilla de usuario creada exitosamente.',
                    'type' => 'success',
                ],
            ]);
        }

        return redirect()->route('users.templates.index')->with([
            'message' => [
                'message' => 'Error al crear la plantilla de usuario.',
                'type' => 'error',
            ],
        ]);
    }

    /**
     * Update an existing user template.
     *
     * @param \App\Http\Requests\BaseFormRequest $request
     * @param \App\Models\Users\Templates\UserTemplate $userTemplate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BaseFormRequest $request, UserTemplate $userTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'description.max' => 'La descripción no puede exceder los 1000 caracteres.',
        ]);

        if($userTemplate->update($request->only(['name', 'description']))){
            return redirect()->route('users.templates.show', ['userTemplate' => $userTemplate])->with([
                'message' => [
                    'message' => 'Plantilla de usuario actualizada exitosamente.',
                    'type' => 'success',
                ],
            ]);
        }

        return redirect()->route('users.templates.index')->with([
            'message' => [
                'message' => 'Error al actualizar la plantilla de usuario.',
                'type' => 'error',
            ],
        ]);
    }

    /**
     * Delete a user template.
     *
     * @param \App\Models\Users\Templates\UserTemplate $userTemplate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(UserTemplate $userTemplate)
    {
        if($userTemplate->delete()){
            return redirect()->route('users.templates.index')->with([
                'message' => [
                    'message' => 'Plantilla de usuario eliminada exitosamente.',
                    'type' => 'success',
                ],
            ]);
        }

        return redirect()->route('users.templates.index')->with([
            'message' => [
                'message' => 'Error al eliminar la plantilla de usuario.',
                'type' => 'error',
            ],
        ]);
    }
}
