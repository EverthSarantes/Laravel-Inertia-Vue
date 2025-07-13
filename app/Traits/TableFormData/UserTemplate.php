<?php

namespace App\Traits\TableFormData;

use App\Traits\TableFormData\TableFormDataTrait;

/**
 * Trait UserTemplate
 * Provides functionality for managing user-related data in tables and forms.
 */
trait UserTemplate
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
        'description',
    ];

    /**
     * @var array<string> Fields in the table that can be searched.
     */
    public static $table_fields_searchable = [
        'name',
        'description',
    ];

    /**
     * @var array<string, string> Field names for the table.
     */
    public static $table_fields_names = [
        'name' => 'Nombre',
        'description' => 'Descripción',
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
            'input_type' => 'input',
            'type' => 'text',
            'required' => true,
            'readonly' => false,
            'label' => 'Nombre',
            'name' => 'name',
            'id' => 'user_name',
            'placeholder' => 'Nombre',
        ],
        'description' => [
            'input_type' => 'textarea',
            'type' => 'text',
            'required' => false,
            'readonly' => false,
            'label' => 'Descripción',
            'name' => 'description',
            'id' => 'user_description',
            'placeholder' => 'Descripción',
        ],
    ];
}