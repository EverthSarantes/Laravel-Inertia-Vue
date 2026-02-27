<?php

namespace App\Traits\TableFormData;

use App\Traits\TableFormData\TableFormDataTrait;

/**
 * Trait User
 * Provides functionality for managing user-related data in tables and forms.
 */
trait User
{
    use TableFormDataTrait;

    /**
     * @var array<string> Modules that the user has permission to access.
     */
    public static $permisson_modules = [
        'users',
    ];

    /**
     * @var array<string> Fields to display in the table.
     */
    public static $table_fields = [
        'name',
        'role',
        'can_login',
    ];

    /**
     * @var array<string> Fields in the table that can be searched.
     */
    public static $table_fields_searchable = [
        'name',
        'role',
    ];

    /**
     * @var array<string, string> Field names for the table.
     */
    public static $table_fields_names = [
        'name' => 'Nombre',
        'email' => 'Correo',
        'role' => 'Rol',
        'can_login' => 'Puede iniciar sesión',
    ];

    /**
     * @var array<string, string> Field types for the table.
     */
    public static $table_fields_types = [
        'name' => 'text',
        'email' => 'text',
        'role' => 'text',
        'can_login' => 'text',
    ];

    /**
     * @var array<string, string> Fields that can be morphed.
     */
    public static $morphable_fiels = [
        'role' => 'rol_name',
        'can_login' => 'can_login_name',
    ];

    /**
     * @var array<string, string> Role names mapped to their IDs.
     */
    public static $rol_name = [
        '0' => 'Administrador',
        '1' => 'Usuario',
    ];

    public static $can_login_name = [
        '0' => 'No',
        '1' => 'Sí',
    ];

    /**
     * @var string The field name representing the model's name.
     */
    public static $name_field = 'name';

    /**
     * Generates options for the field content in the table.
     *
     * @return array<string, array<string, mixed>> The options for the field content.
     */
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

    /**
     * @var array<string, array<string, mixed>> Form fields configuration.
     * Defines the input type, validation rules, and other attributes for each field.
     */
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
        'can_login' => [
            'input_type' => 'select',
            'type' => 'text',
            'required' => true,
            'readonly' => false,
            'label' => 'Puede iniciar sesión',
            'name' => 'can_login',
            'id' => 'can_login',
            'placeholder' => 'Puede iniciar sesión',
            'options_function' => 'getCanLoginOptions',
            'options' => [
                '1' => 'Sí',
                '0' => 'No',
            ],
        ],
    ];

    /**
     * Retrieves the available roles.
     *
     * @return array<string, string> The roles mapped to their IDs.
     */
    public static function getRoles()
    {
        return self::$rol_name;
    }

    /**
     * Retrieves the role name for the current user.
     *
     * @return string|null The role name, or null if not found.
     */
    public function rol()
    {
        return self::$rol_name[$this->role] ?? null;
    }
}