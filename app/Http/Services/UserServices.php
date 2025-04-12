<?php
namespace App\Http\Services;

use App\Models\User;
use App\Models\Users\UserModule;

class UserServices
{
    /**
     * Create a new user with the provided request data.
     * If the user has a role of 1, modules can also be assigned.
     *
     * @param \Illuminate\Http\Request $request
     * @return User|null The created user or null if creation fails.
     */
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

    /**
     * Update an existing user's information.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return User|null The updated user or null if update fails.
     */
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

    /**
     * Add a module to a user if the user has the appropriate role.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return UserModule|bool The created UserModule or false if addition fails.
     */
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