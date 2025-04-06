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

class UsersController extends Controller
{
    public function index($pagination = 20)
    {
        $modules = Module::select('id', 'name')->get();

        return Inertia::render('users.index', [
            'model' => User::getStaticData(),
            'form_modules' => $modules,
        ]);
    }

    public function show(User $user)
    {
        return Inertia::render('users.show', [
            'user' => $user,
            'model' => UserModule::getStaticData(),
        ]);
    }

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
