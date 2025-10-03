<?php

namespace App\Models\Configurations;

use Illuminate\Database\Eloquent\Model;
use Everth\UserStamps\UserStampsTrait;

class Configuration extends Model
{
    use UserStampsTrait;

    protected $fillable = ['name', 'key', 'value', 'type'];

    protected $appends = ['typed_value'];

    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'boolean' => filter_var((bool) $this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'array' => json_decode($this->value, true),
            default => $this->value,
        };
    }
}
