<?php

namespace App\Models\Configurations;

use Illuminate\Database\Eloquent\Model;
use Everth\UserStamps\UserStampsTrait;

class Configuration extends Model
{
    use UserStampsTrait;

    protected $fillable = ['key', 'value', 'type'];
}
