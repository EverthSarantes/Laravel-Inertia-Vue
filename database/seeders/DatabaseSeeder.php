<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users\Module;
use App\Models\Backups\ScheduledBackup;
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

        $modules = [
            [
                'name' => 'Usuarios',
                'internal_name' => 'users',
                'access_route_name' => 'users.index',
                'icon' => 'bx bx-group nav_icon',
            ],
            [
                'name' => 'Respaldos',
                'internal_name' => 'backups',
                'access_route_name' => 'backups.index',
                'icon' => 'bx bx-save nav_icon',
            ],
        ];

        foreach ($modules as $module) {
            Module::create([
                'name' => $module['name'],
                'internal_name' => $module['internal_name'],
                'access_route_name' => $module['access_route_name'],
                'icon' => $module['icon'],
            ]);
        }
    }
}
