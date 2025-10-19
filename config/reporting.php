<?php

use App\Models\User;
use App\Models\Users\UserModule;
use App\Models\Users\Module;

return [
    'models' => [
        'users' => [
            'class' => User::class,
            'label' => 'Usuarios',
            'fields' => [
                'id' => 'ID',
                'name' => 'Nombre',
                'role' => 'Rol',
                'can_login' => 'Puede Iniciar Sesión',
                'created_at' => 'Fecha de Creación',
                'updated_at' => 'Fecha de Actualización',
            ],
            'relations' => [
                'userModules' => [
                    'relation_type' => 'hasMany',
                ],
                'userModules.modules' => [
                    'relation_type' => 'hasManyThrough',
                ],
            ],
        ],
        'userModules' => [
            'class' => UserModule::class,
            'label' => 'Módulos de Usuario',
            'fields' => [
                'id' => 'ID',
                'user_id' => 'ID de Usuario',
                'module_id' => 'ID de Módulo',
                'created_at' => 'Fecha de Creación',
                'updated_at' => 'Fecha de Actualización',
            ],
            'relations' => [
                'users' => [
                    'relation_type' => 'belongsTo',
                ],
                'modules' => [
                    'relation_type' => 'belongsTo',
                ],
            ],
        ],
        'modules' => [
            'class' => Module::class,
            'label' => 'Módulos',
            'fields' => [
                'id' => 'ID',
                'name' => 'Nombre',
                'internal_name' => 'Nombre Interno',
                'access_route_name' => 'Ruta de Acceso',
                'icon' => 'Ícono',
                'order' => 'Orden',
                'show_in_menu' => 'Mostrar en Menú',
                'created_at' => 'Fecha de Creación',
                'updated_at' => 'Fecha de Actualización',
            ],
            'relations' => [
                'userModules' => [
                    'relation_type' => 'hasMany',
                ],
            ],
        ],
    ],
];