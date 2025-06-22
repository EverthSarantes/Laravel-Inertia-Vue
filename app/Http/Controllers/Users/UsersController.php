<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UsersStoreRequest;
use App\Http\Requests\BaseFormRequest;
use App\Http\Services\UserServices;
use App\Models\Users\Module;
use App\Models\Users\UserModule;
use Inertia\Inertia;

/**
 * Manages user-related operations such as CRUD and module assignments.
 */
class UsersController extends Controller
{
    /**
     * Display a paginated list of users.
     *
     * @param int $pagination
     * @return \Inertia\Response
     */
    public function index($pagination = 20)
    {
        $modules = Module::select('id', 'name')->get();

        return Inertia::render('users.index', [
            'model' => User::getStaticData(),
            'form_modules' => $modules,
        ]);
    }

    /**
     * Display details of a specific user.
     *
     * @param \App\Models\User $user
     * @return \Inertia\Response
     */
    public function show(User $user)
    {
        $user->load(['userModule.actions', 'userModule.module']);
        return Inertia::render('users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Store a new user in the database.
     *
     * @param \App\Http\Requests\UsersStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UsersStoreRequest $request)
    {
        $request->validated();

        $response = UserServices::makeUser($request);

        if($response === null)
        {
            return redirect()->route('users.index')->with([
                'message' => [
                    'message' => 'Error al crear el usuario',
                    'type' => 'danger'
                ],
            ]);
        }

        return redirect()->route('users.index')->with([
            'message' => [
                'message' => 'Usuario creado correctamente',
                'type' => 'success'
            ],
        ]);
    }

    /**
     * Update an existing user's information.
     *
     * @param \App\Http\Requests\BaseFormRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BaseFormRequest $request, User $user)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'El campo nombre es obligatorio',
        ]);

        $response = UserServices::updateUser($request, $user);

        if($response === null)
        {
            return redirect()->back()->with([
                'message' => [
                    'message' => 'Error al actualizar el usuario',
                    'type' => 'danger',
                ],
            ]);
        }

        return redirect()->back()->with([
            'message' => [
                'message' => 'Usuario actualizado correctamente',
                'type' => 'success',
            ],
        ]);
    }

    /**
     * Delete a user from the database.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with([
            'message' => [
                'message' => 'Usuario eliminado correctamente',
                'type' => 'success'
            ],
        ]);
    }

    /**
     * Assign a module to a user.
     *
     * @param \App\Http\Requests\BaseFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addModule(BaseFormRequest $request)
    {
        $request->validate([
            'module_id' => 'required',
            'user_id' => 'required',
        ], [
            'module_id.required' => 'El campo módulo es obligatorio',
            'user_id.required' => 'El usuario es obligatorio',
        ]);

        $user = User::find($request->user_id);

        if($user === null)
        {
            return redirect()->route('users.index')->with([
                'message' => [
                    'message' => 'Usuario no encontrado',
                    'type' => 'danger',
                ],
            ]);
        }

        $response = UserServices::addModule($request, $user);

        if(!$response)
        {
            return redirect()->back()->with([
                'message' => [
                    'message' => 'Error al agregar el módulo',
                    'type' => 'danger',
                ],
            ]);
        }

        return redirect()->back()->with([
            'message' => [
                'message' => 'Módulo agregado correctamente',
                'type' => 'success',
            ],
        ]);
    }

    /**
     * Remove a module from a user.
     *
     * @param \App\Models\Users\UserModule $userModule
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteModule(UserModule $userModule, User $user)
    {
        $userModule->delete();

        return redirect()->route('users.show', $user)->with([
            'message' => [
                'message' => 'Módulo eliminado correctamente',
                'type' => 'success',
            ],
        ]);
    }
}
