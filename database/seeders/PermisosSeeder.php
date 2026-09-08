<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permisos = [
            'ordenes.ver',
            'ordenes.crear',
            'ordenes.editar',
            'ordenes.eliminar',
            'ordenes.servicios',
            'ordenes.adicionales',
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.eliminar',
            'estados.ver',
            'estados.cambiar',
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.toggle',
            'roles.ver',
            'roles.crear',
            'roles.editar',
            'roles.eliminar',
            'roles.permisos',
            'garantias.ver',
            'garantias.usar',
            'ingresos.ver',
            'reportes.ver',
            'reportes.pdf',
            'reportes.excel',
            'tipos_equipo.ver',
            'tipos_equipo.crear',
            'tipos_equipo.editar',
            'tipos_equipo.eliminar',
            'servicios.ver',
            'servicios.crear',
            'servicios.editar',
            'servicios.eliminar',
            // Dashboard
            'dashboard.ordenes',   // tarjetas y tabla de órdenes
            'dashboard.ingresos',  // tarjeta ingresos + gráfico 6 meses
            'dashboard.graficos',  // actividad 7 días + top servicios
            'dashboard.tecnicos',  // ranking de técnicos
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        // ADMINISTRADOR — todo
        $admin = Role::firstOrCreate(['name' => 'ADMINISTRADOR', 'guard_name' => 'web']);
        $admin->syncPermissions($permisos);

        // TECNICO
        $tecnico = Role::firstOrCreate(['name' => 'TECNICO', 'guard_name' => 'web']);
        $tecnico->syncPermissions([
            'ordenes.ver',
            'ordenes.editar',
            'ordenes.servicios',
            'ordenes.adicionales',
            'clientes.ver',
            'estados.ver',
            'estados.cambiar',
            'garantias.ver',
            'dashboard.ordenes',
            'dashboard.graficos',
        ]);

        // RECEPCION
        $recepcion = Role::firstOrCreate(['name' => 'RECEPCION', 'guard_name' => 'web']);
        $recepcion->syncPermissions([
            'ordenes.ver',
            'ordenes.crear',
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'estados.ver',
            'garantias.ver',
            'dashboard.ordenes',
        ]);

        $this->command->info('✅ Permisos creados y asignados correctamente.');
    }
}
