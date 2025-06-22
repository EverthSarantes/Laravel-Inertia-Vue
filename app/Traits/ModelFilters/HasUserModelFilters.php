<?php

namespace App\Traits\ModelFilters;

use Illuminate\Database\Eloquent\Builder;

trait HasUserModelFilters
{
    public const TYPE_SIMPLE = 'simple';
    public const TYPE_RELATIONS = 'relations';
    public const TYPE_FUNCTIONS = 'functions';

    public const TYPE_OPEN = 'open';
    public const TYPE_STATIC_SELECT = 'static_select';
    public const TYPE_DYNAMIC_SELECT = 'dynamic_select';

    public const OP_EQUAL = '=';
    public const OP_NOT_EQUAL = '!=';
    public const OP_GREATER = '>';
    public const OP_LESS = '<';
    public const OP_GREATER_OR_EQUAL = '>=';

    protected static function bootHasUserModelFilters()
    {
        static::addGlobalScope('user_model_filters', function (Builder $builder) {
            
        });
    }
}