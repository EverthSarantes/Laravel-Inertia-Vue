<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Everth\UserStamps\UserStampsTrait;
use App\Traits\TableFormData\Module as TableFormDataModule;

class Module extends Model
{
    use HasFactory;
    use UserStampsTrait;
    use TableFormDataModule;

    protected $fillable = [
        'name',
        'internal_name',
        'access_route_name',
        'icon',
    ];

    public function userModule()
    {
        return $this->hasMany(UserModule::class);
    }
}
