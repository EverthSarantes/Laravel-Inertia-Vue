<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users\Module;
use App\Models\Backups\ScheduledBackup;
use App\Models\Configurations\Configuration;
use App\Models\Users\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'admin',
            'password' => bcrypt('admin'),
            'role' => '0',
        ]);

        ScheduledBackup::create([
            'days' => [0],
            'times' => ['17:00'],
            'active' => false,
        ]);

        $app = App::create([
            'name' => 'Administración',
            'internal_name' => 'administration_app',
            'access_route_name' => 'administration_app.index',
            'icon' => 'https://placehold.co/600x400',
            'order' => 1,
            'show_in_menu' => true,
        ]);

        $modules = [
            [
                'name' => 'Usuarios',
                'internal_name' => 'users',
                'access_route_name' => 'users.index',
                'icon' => 'bx bx-group nav_icon',
                'order' => 1,
                'show_in_menu' => true,
            ],
            [
                'name' => 'Respaldos',
                'internal_name' => 'backups',
                'access_route_name' => 'backups.index',
                'icon' => 'bx bx-save nav_icon',
                'order' => 2,
                'show_in_menu' => true,
            ],
            [
                'name' => 'Reportes',
                'internal_name' => 'reports',
                'access_route_name' => 'reports.index',
                'icon' => 'bx bx-bar-chart-alt-2 nav_icon',
                'order' => 3,
                'show_in_menu' => true,
            ],
            [
                'name' => 'Configuración',
                'internal_name' => 'config',
                'access_route_name' => 'config.index',
                'icon' => 'bx bx-cog nav_icon',
                'order' => 4,
                'show_in_menu' => true,
            ],
        ];

        foreach ($modules as $module) {
            Module::create([
                'name' => $module['name'],
                'internal_name' => $module['internal_name'],
                'access_route_name' => $module['access_route_name'],
                'icon' => $module['icon'],
                'order' => $module['order'],
                'show_in_menu' => $module['show_in_menu'],
                'app_id' => $app->id,
            ]);
        }

        $configurations = [
            ['name' => 'Eliminar Temporalmente', 'key' => 'global_use_soft_deletes', 'value' => 'false', 'type' => 'boolean'],
            ['name' => 'Permitir Sesiones de Redes Sociales', 'key' => 'global_use_social_login', 'value' => 'false', 'type' => 'boolean'],
        ];

        foreach ($configurations as $configuration) {
            Configuration::create([
                'name' => $configuration['name'],
                'key' => $configuration['key'],
                'value' => $configuration['value'],
                'type' => $configuration['type'],
            ]);
        }
    }
}
