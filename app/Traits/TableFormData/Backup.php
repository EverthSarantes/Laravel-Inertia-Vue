<?php

namespace App\Traits\TableFormData;

use App\Traits\TableFormData\TableFormDataTrait;

/**
 * Trait User
 * Provides functionality for managing backup-related data in tables and forms.
 */
trait Backup
{
    use TableFormDataTrait;

    /**
     * @var array<string> Modules that the user has permission to access.
     */
    public static $permisson_modules = [
        'backups',
    ];

    /**
     * @var array<string> Fields to display in the table.
     */
    public static $table_fields = [
        'name',
    ];

    /**
     * @var array<string> Fields in the table that can be searched.
     */
    public static $table_fields_searchable = [
        'name',
    ];

    /**
     * @var array<string, string> Field names for the table.
     */
    public static $table_fields_names = [
        'name' => 'Nombre',
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
        if(!$this->name) {
            return [];
        }

        return [
            'show' => [
                'type' => 'normal-link',
                'attr' => [
                    'href' => route('backups.download', ['name' => $this->name]),
                    'class' => 'bg-green-100 dark:bg-green-700 text-green-700 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-600',
                ],
                'inner' => '<i class=\'bx bxs-download\'></i>',
            ],
            'delete' => [
                'type' => 'button',
                'attr' => [
                    'class' => 'bg-red-100 dark:bg-red-700 text-red-700 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-600',
                    'data-url' => route('backups.delete', ['name' => $this->name]),
                ],
                'inner' => '<i class=\'bx bxs-trash-alt\'></i>',
            ],
        ];
    }
}