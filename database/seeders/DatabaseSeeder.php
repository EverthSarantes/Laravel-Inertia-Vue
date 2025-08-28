<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users\Module;
use App\Models\Backups\ScheduledBackup;
use App\Models\Users\App;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        if(env('DB_CONNECTION') == 'mysql')
        {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            Module::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } 
        else{
            User::truncate();
            Module::truncate();
        }

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
            'icon' => 'bx bx-app nav_icon',
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
    }
}
