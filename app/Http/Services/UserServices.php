<?php
namespace App\Http\Services;

use App\Models\User;
use App\Models\Users\UserModule;

class UserServices
{
    public static function makeUser($request)
    {
        if($request->role === 1)
        {
            $user = User::create([
                'name' => $request['name'],
                'password' => bcrypt($request['password']),
                'role' => $request['role'],
            ]); 

            if($request->modules !== null)
            {
                foreach($request->modules as $module)
                {
                    $user->userModule()->create([
                        'module_id' => $module,
                    ]);
                }
            }

            return $user;
        }
        else if($request->role === 0)
        {
            $user = User::create([
                'name' => $request['name'],
                'password' => bcrypt($request['password']),
                'role' => $request['role'],
            ]);

            return $user;
        }

        return null;
    }

    public static function updateUser($request, User $user)
    {
        $user->name = $request['name'];
        if($request->password !== null)
        {
            $user->password = bcrypt($request['password']);
        }
        
        if($user->save())
        {
            return $user;
        }

        return null;
    }

    public static function addModule($request, User $user)
    {
        if($user->role === '0')
        {
            return false;
        }

        if(UserModule::where('user_id', $user->id)->where('module_id', $request->module_id)->exists())
        {
            return false;
        }

        return UserModule::create([
            'user_id' => $user->id,
            'module_id' => $request->module_id,
        ]);
    }
}