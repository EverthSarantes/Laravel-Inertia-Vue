<?php

namespace App\Http\Services\Api;

class SearchServices
{
    private static function getKeyByValue(array $array, $value)
    {
        foreach ($array as $key => $item) {
            if ($item === $value) {
                return $key;
            }
        }
    }

    private static function addWhere($field, $search_type, $search, $query, $model)
    {
        if (isset($model::$morphable_fiels[$field])) {
            $morphField = $model::$morphable_fiels[$field];
            if (isset($model::$$morphField)) {
                $search = self::getKeyByValue($model::$$morphField, $search);
            }
        }

        if(isset($model::$searchable_fields_is_relation[$field]) && $model::$searchable_fields_is_relation[$field]) {
            $query->whereHas($field, function ($query) use ($search_type, $search, $model, $field) {
                if ($search_type == 'like')
                {
                    $query->where($model::$relation_fields[$field]['foreign_field'], $search_type, "%$search%");
                }
                else
                {
                    $query->where($model::$relation_fields[$field]['foreign_field'], $search_type, $search);
                }
            });
        } else {
            if ($search_type == 'like')
            {
                $query->where($field, $search_type, "%$search%");
            }
            else
            {
                $query->where($field, $search_type, $search);
            }
        }

        return $query;
    }

    public static function verifyFieldIsSearchable(string $model, string $field = null): bool
    {
        if($field === null) {
            return true;
        }

        return in_array($field, $model::$table_fields_searchable);
    }

    public static function verifyUsersHasPermission(string $model): bool
    {
        $userModules = auth()->user()->modules();
        $modelModules = $model::$permisson_modules;

        foreach ($modelModules as $module) {
            if($userModules->contains('internal_name', $module)) {
                return true;
            }
        }
        return false;
    }

    public static function search(string $model, $extra_query_parameter = null, $pagination = 20, $params = null)
    {
        $permited_search_types = ['like', '=', '!=', '>', '<', '>=', '<='];

        foreach ($params as $param) {
            if(!in_array($param['search_type'], $permited_search_types)) {
                return false;
            }

            if(!self::verifyFieldIsSearchable($model, $param['field'])) {
                return false;
            }
        }

        $query = $model::query();

        foreach ($params as $param) {
            if($param['field'] && $param['search_type'] && $param['search']){
                $query = self::addWhere($param['field'], $param['search_type'], $param['search'], $query, $model);
            }
        }

        if(isset($model::$relation_fields))
        {
            foreach ($model::$relation_fields as $relation_field) {
                $query->with($relation_field['relation']);
            }
        }

        self::loadExtraQuery($model, $query, $extra_query_parameter);

        return $query->paginate($pagination);
    }

    public static function searchSelect(string $model, string $search)
    {
        $query = $model::query();

        if($search != "*")
        {
            $query->where($model::$name_field, 'like', "%$search%");
        }

        $data = $query->get();

        $data = self::loadSearchName($data);

        return $data;
    }

    public static function loadExtraQuery($model, $query, $extra_query_parameter)
    {
        if(method_exists($model, 'extraQuery')) {
            $model::extraQuery($query, $extra_query_parameter);
        }
    }

    public static function loadMorphableFields(string $model, $data)
    {
        if(!isset($model::$morphable_fiels)) {
            return $data;
        }

        foreach ($model::$morphable_fiels as $field => $morphable_name) {
            $data->map(function ($item) use ($field, $morphable_name, $model) {
                $item->$field = $model::$$morphable_name[$item->$field];
                return $item;
            });
        }

        return $data;
    }

    public static function loadOptionsFieldContent($data)
    {
        $data->map(function ($item) {
            $item->options = $item->getOptionsFieldContent();
            return $item;
        });

        return $data;
    }

    public static function loadSearchName($data)
    {
        $data->map(function ($item) {
            if($item::$name_field) {
                $item->name = $item->{$item::$name_field};
            }
            if (method_exists($item, 'getSearchName')) {
                $item->name = $item->getSearchName();
            }
            return $item;
        });

        return $data;
    }

    public static function loadRelationFields($data)
    {
        $data->map(function ($item) {
            if(isset($item::$relation_fields)) {
                foreach ($item::$relation_fields as $relation_field) {
                    $item->{$relation_field['field']} = $item->{$relation_field['relation']}->{$relation_field['foreign_field']};
                    $item->unsetRelation($relation_field['relation']);
                }
            }
        });

        return $data;
    }
}