<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Crear el rol "admin" si no existe
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Obtener el usuario con ID 1
        $user = User::find(1);

        if ($user) {
            // Asignar el rol "admin" al usuario
            $user->assignRole($adminRole);

            // Obtener todos los permisos disponibles
            $permissions = Permission::pluck('id')->all();

            // Asignar todos los permisos al rol "admin"
            $adminRole->syncPermissions($permissions);

            $this->command->info('Rol "admin" asignado al usuario con ID 1 con todos los permisos.');
        } else {
            $this->command->error('No se encontró un usuario con ID 1.');
        }
    }
}
