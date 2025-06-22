<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Everth\UserStamps\UserStampsTrait;

class UserModuleAction extends Model
{
    use UserStampsTrait;

    protected $table = 'users_modules_actions';

    protected $fillable = [
        'user_module_id',
        'action',
    ];
    public function userModule()
    {
        return $this->belongsTo(UserModule::class, 'user_module_id');
    }
}
