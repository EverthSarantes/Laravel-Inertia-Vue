<?php

namespace App\Traits\TableFormData;

use Illuminate\Support\Facades\Route;
use App\Traits\TableFormData\TableFormDataTrait;

trait UserModule
{
    use TableFormDataTrait;

    public static $permisson_modules = [
        'users',
    ];

    public static $name_field = 'module_id';

    public static $table_fields_searchable = [
        'module',
    ];

    public static $searchable_fields_is_relation = [
        'module' => true,
    ];

    public static $table_fields = [
        'module_name',
        'user_name',
    ];

    public static $table_fields_names = [
        'module_name' => 'Módulo',
        'user_name' => 'Usuario',
        'module_id' => 'Módulo',
        'module' => 'Módulo',
    ];

    public static $relation_fields = [
        'module' => [
            'relation' => 'module',
            'field' => 'module_name',
            'foreign_field' => 'name',
        ],
        'user' => [
            'relation' => 'user',
            'field' => 'user_name',
            'foreign_field' => 'name',
        ],
    ];

    public static function extraQuery($query, $extra_query_parameter)
    {
        return $query->where('user_id', $extra_query_parameter);
    }

    public function getOptionsFieldContent()
    {
        $show_route = Route::has($this->module->access_route_name) ? route($this->module->access_route_name) : '';
        $delete_route = route('users.deleteModule', ['userModule' => $this , 'user' => $this->user]);
        
        return [
            'show' => [
                'type' => 'link',
                'attr' => [
                    'href' => $show_route,
                    'class' => 'btn btn-primary',
                ],
                'inner' => '<i class=\'bx bxs-show\'></i>',
            ],
            'delete' => [
                'type' => 'button',
                'attr' => [
                    'class' => 'delete-button btn btn-danger',
                    'data-url' => $delete_route,
                ],
                'inner' => '<i class=\'bx bxs-trash-alt\'></i>',
            ],
        ];
    }
}