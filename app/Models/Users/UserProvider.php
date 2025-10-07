<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Everth\UserStamps\UserStampsTrait;
use App\Models\User;
use App\Traits\ModelSoftDeleteTrait;

class UserProvider extends Model
{
    use UserStampsTrait, ModelSoftDeleteTrait;

    protected $fillable = [
        'user_id',
        'provider_name',
        'provider_id',
        'provider_email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
