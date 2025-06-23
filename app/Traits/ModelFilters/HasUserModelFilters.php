<?php

namespace App\Traits\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

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

    protected static bool $shouldApplyUserFilters = false;

    public static function enableUserModelFilters(): void
    {
        static::$shouldApplyUserFilters = true;
    }

    protected static function bootHasUserModelFilters()
    {
        static::addGlobalScope('user_model_filters', function (Builder $builder) {

            if (!static::$shouldApplyUserFilters) {
                return;
            }

            $className = static::getActualModelName();
            $userModelFilters = static::getUserModelFiltersByModel($className);
            foreach ($userModelFilters as $userModelFilter) {
                if($userModelFilter['comparison_type'] == static::TYPE_SIMPLE) {
                    $builder = static::setSimpleFilter($builder, $userModelFilter['field'], $userModelFilter['operator'], $userModelFilter['value']);
                }
            }
        });
    }

    protected static function getActualModelName(): string
    {
        $className = (new \ReflectionClass(static::class))->getShortName();
        $modelFiltersConfig = config('modelFilters');

        if (!isset($modelFiltersConfig[$className])) {
            throw new \Exception("Model filters configuration for {$className} not found.");
        }
        return $modelFiltersConfig[$className]['model'];
    }

    protected static function getShortModelName(): string
    {
        return (new \ReflectionClass(static::class))->getShortName();
    }

    protected static function getUserModelFiltersByModel(string $model): array
    {
        $userId = static::getAuthenticatedUserId();

        /* if (!$userId) {
            $modelFiltersConfig = config('modelFilters');
            $modelConfig = $modelFiltersConfig[static::getShortModelName()] ?? [];
            $behavior = $modelConfig['unauthenticated_behavior'] ?? 'exception';

            return match ($behavior) {
                //sin filtros
                'none' => [],
                //filtros por defecto
                'default' => $modelConfig['default_filters'] ?? throw new \Exception('Default filters not defined for unauthenticated users.'),
                //lanzar excepción
                'exception' => throw new \Exception('User not authenticated.'),
                default => throw new \Exception('Invalid unauthenticated behavior specified.'),
            };
        } */

        return collect(DB::table('user_model_filters')
            ->where('user_id', $userId)
            ->where('model', $model)
            ->get()
            ->toArray())->map(function ($item) {
                return (array) $item;
            })->toArray();
    }

    protected static function getAuthenticatedUserId(): ?int
    {
        $cookieName = Config::get('session.cookie');
        $sessionId = request()->cookie($cookieName);

        if (!$sessionId) {
            return null;
        }

        $session = DB::table('sessions')
            ->where('id', $sessionId)
            ->first();

        if (!$session || !$session->user_id) {
            return null;
        }

        return $session->user_id;
    }

    protected static function setSimpleFilter(Builder $builder, string $field, string $operator, mixed $value): Builder
    {
        return $builder->where($field, $operator, $value);
    }
}