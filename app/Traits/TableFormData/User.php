<?php

namespace App\Traits\TableFormData;

use App\Traits\TableFormData\TableFormDataTrait;

trait User
{
    use TableFormDataTrait;
    //modules that the user has permission to access this model in the search
    public static $permisson_modules = [
        'users',
    ];

    //options for the automatic table
    //fields for the table
    public static $table_fields = [
        'name',
        'role',
    ];

    //fields that can be searched
    public static $table_fields_searchable = [
        'name',
        'role',
    ];

    //fields names for the table
    public static $table_fields_names = [
        'name' => 'Nombre',
        'email' => 'Correo',
        'role' => 'Rol',
    ];

    //fields that can be morphed
    public static $morphable_fiels = [
        'role' => 'rol_name',
    ];

    public static $rol_name = [
        '0' => 'Administrador',
        '1' => 'Usuario',
    ];

    //field that is the name of the model
    public static $name_field = 'name';

    //get the content of the field options of the table
    public function getOptionsFieldContent()
    {
        return [
            'show' => [
                'type' => 'link',
                'attr' => [
                    'href' => route('users.show', ['user' => $this]),
                    'class' => 'btn btn-primary',
                ],
                'inner' => '<i class=\'bx bxs-show\'></i>',
            ],
            'delete' => [
                'type' => 'button',
                'attr' => [
                    'class' => 'delete-button btn btn-danger',
                    'data-url' => route('users.delete', ['user' => $this]),
                ],
                'inner' => '<i class=\'bx bxs-trash-alt\'></i>',
            ],
        ];
    }

    //options for the automatic form
    //fields for the form
    public static $form_fields = [
        'name' => [
            'input_type' => 'input', //input, textarea, select
            'type' => 'text',
            'required' => true,
            'readonly' => false,
            'label' => 'Nombre',
            'name' => 'name',
            'id' => 'user_name',
            'placeholder' => 'Nombre',
        ],
        'password' => [
            'input_type' => 'input',
            'type' => 'password',
            'required' => true,
            'readonly' => false,
            'label' => 'Contraseña',
            'name' => 'password',
            'id' => 'user_password',
            'placeholder' => 'Contraseña',
        ],
        'role' => [
            'input_type' => 'select',
            'type' => 'text',
            'required' => true,
            'readonly' => false,
            'label' => 'Rol',
            'name' => 'role',
            'id' => 'role',
            'placeholder' => 'Rol',
            'options_function' => 'getRoles',
            'options' => [
                '0' => 'Administrador',
                '1' => 'Usuario',
            ],
        ],
    ];


    public static function getRoles()
    {
        return self::$rol_name;
    }

    public function rol()
    {
        return self::$rol_name[$this->role] ?? null;
    }
}