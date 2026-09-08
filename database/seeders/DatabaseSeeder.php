<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //  LLAMAR PRIMERO A LOS PERMISOS
        $this->call(PermisosSeeder::class);

        // Crear roles (opcional si ya los crea PermisosSeeder)
        $adminRole = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
        $tecnicoRole = Role::firstOrCreate(['name' => 'TECNICO']);
        $recepcionRole = Role::firstOrCreate(['name' => 'RECEPCION']);

        // Crear usuario SOLO SI NO EXISTE
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Miguel Angel',
                'password' => bcrypt('12345678'),
            ]
        );

        //  ROL ADMIN (IMPORTANTE)
        $user->syncRoles([$adminRole]);
    }
}
