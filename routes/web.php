<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('home.index');
Route::get('/consulta', [App\Http\Controllers\ConsultaController::class, 'index'])->name('consulta.index');
Route::post('/consulta', [App\Http\Controllers\ConsultaController::class, 'buscar'])->name('consulta.buscar');

Route::get('/home', [App\Http\Controllers\AdminController::class, 'index'])->name('home')->middleware('auth');

// Chatbot (sin auth, fuera del grupo)
Route::post('/chatbot/buscar',   [App\Http\Controllers\ChatbotController::class, 'buscar'])->middleware('throttle:20,1');
Route::get('/chatbot/servicios', [App\Http\Controllers\ChatbotController::class, 'servicios']);
Route::post('/chatbot/ia',       [App\Http\Controllers\ChatbotController::class, 'ia'])->middleware('throttle:30,1');

// ══════════════════════════════════════════════════════════════
//  GRUPO ADMIN — todas las rutas requieren auth
// ══════════════════════════════════════════════════════════════
Route::prefix('admin')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/actividad',     [App\Http\Controllers\AdminController::class, 'actividadPeriodo'])->name('admin.dashboard.actividad');
    Route::get('/dashboard/top-servicios', [App\Http\Controllers\AdminController::class, 'topServiciosPeriodo'])->name('admin.dashboard.top_servicios');
    Route::get('/dashboard/ordenes-estado', [App\Http\Controllers\AdminController::class, 'ordenesEstadoPeriodo'])->name('admin.dashboard.ordenes_estado');

    // Perfil — sin permiso especial, cualquier usuario autenticado
    Route::get('/perfil',          [App\Http\Controllers\PerfilController::class, 'index'])->name('admin.perfil.index');
    Route::put('/perfil',          [App\Http\Controllers\PerfilController::class, 'actualizar'])->name('admin.perfil.actualizar');
    Route::put('/perfil/password', [App\Http\Controllers\PerfilController::class, 'cambiarPassword'])->name('admin.perfil.password');


    // ── Órdenes ───────────────────────────────────────────────
    Route::get('/ordenes',           [App\Http\Controllers\OrdenController::class, 'index'])->name('admin.ordenes.index')->middleware('can:ordenes.ver');
    Route::get('/ordenes/crear',     [App\Http\Controllers\OrdenController::class, 'create'])->name('admin.ordenes.create')->middleware('can:ordenes.crear');
    Route::post('/ordenes',          [App\Http\Controllers\OrdenController::class, 'store'])->name('admin.ordenes.store')->middleware('can:ordenes.crear');
    Route::get('/ordenes/{id}',      [App\Http\Controllers\OrdenController::class, 'show'])->name('admin.ordenes.show')->middleware('can:ordenes.ver');
    Route::get('/ordenes/{id}/edit', [App\Http\Controllers\OrdenController::class, 'edit'])->name('admin.ordenes.edit')->middleware('can:ordenes.editar');
    Route::put('/ordenes/{id}',      [App\Http\Controllers\OrdenController::class, 'update'])->name('admin.ordenes.update')->middleware('can:ordenes.editar');
    Route::delete('/ordenes/{id}',   [App\Http\Controllers\OrdenController::class, 'destroy'])->name('admin.ordenes.destroy')->middleware('can:ordenes.eliminar');

    // ← CORREGIDO: estaba fuera del prefijo ordenes/
    Route::put('/ordenes/{id}/entregar-directo', [App\Http\Controllers\OrdenController::class, 'entregarDirecto'])->name('admin.ordenes.entregar_directo');

    // ← NUEVO: cambiar tipo de atención espera ↔ deja
    Route::put('/ordenes/{id}/cambiar-tipo',     [App\Http\Controllers\OrdenController::class, 'cambiarTipo'])->name('admin.ordenes.cambiar_tipo');

    // Servicios y adicionales de una orden
    Route::post('/ordenes/{id}/servicios',    [App\Http\Controllers\OrdenController::class, 'agregarServicio'])->name('admin.ordenes.agregar_servicio')->middleware('can:ordenes.servicios');
    Route::delete('/ordenes/servicios/{id}',  [App\Http\Controllers\OrdenController::class, 'eliminarServicio'])->name('admin.ordenes.eliminar_servicio')->middleware('can:ordenes.servicios');
    Route::post('/ordenes/{id}/adicionales',  [App\Http\Controllers\OrdenController::class, 'agregarAdicional'])->name('admin.ordenes.agregar_adicional')->middleware('can:ordenes.adicionales');
    Route::put('/adicionales/{id}/aprobar',   [App\Http\Controllers\OrdenController::class, 'aprobarAdicional'])->name('admin.adicionales.aprobar')->middleware('can:ordenes.adicionales');
    Route::put('/adicionales/{id}/rechazar',  [App\Http\Controllers\OrdenController::class, 'rechazarAdicional'])->name('admin.adicionales.rechazar')->middleware('can:ordenes.adicionales');

    // Tickets PDF (solo necesita ver la orden)
    Route::get('/ordenes/{id}/ticket1', [App\Http\Controllers\TicketController::class, 'ticket1'])->name('admin.ticket1')->middleware('can:ordenes.ver');
    Route::get('/ordenes/{id}/ticket2', [App\Http\Controllers\TicketController::class, 'ticket2'])->name('admin.ticket2')->middleware('can:ordenes.ver');

    // ── Estados ───────────────────────────────────────────────
    Route::get('/estados',        [App\Http\Controllers\EstadoController::class, 'index'])->name('admin.estados.index')->middleware('can:estados.ver');
    Route::post('/estados/{id}',  [App\Http\Controllers\EstadoController::class, 'cambiar'])->name('admin.estados.cambiar')->middleware('can:estados.cambiar');

    // ── Clientes ──────────────────────────────────────────────
    Route::get('/clientes',             [App\Http\Controllers\ClienteController::class, 'index'])->name('admin.clientes.index')->middleware('can:clientes.ver');
    Route::get('/clientes/create',      [App\Http\Controllers\ClienteController::class, 'create'])->name('admin.clientes.create')->middleware('can:clientes.crear');
    Route::post('/clientes',            [App\Http\Controllers\ClienteController::class, 'store'])->name('admin.clientes.store')->middleware('can:clientes.crear');
    Route::get('/clientes/{id}',        [App\Http\Controllers\ClienteController::class, 'show'])->name('admin.clientes.show')->middleware('can:clientes.ver');
    Route::get('/clientes/{id}/edit',   [App\Http\Controllers\ClienteController::class, 'edit'])->name('admin.clientes.edit')->middleware('can:clientes.editar');
    Route::put('/clientes/{id}',        [App\Http\Controllers\ClienteController::class, 'update'])->name('admin.clientes.update')->middleware('can:clientes.editar');
    Route::delete('/clientes/{id}',     [App\Http\Controllers\ClienteController::class, 'destroy'])->name('admin.clientes.destroy')->middleware('can:clientes.eliminar');
    Route::get('/clientes/{id}/historial', [App\Http\Controllers\ClienteController::class, 'historial'])->name('admin.clientes.historial')->middleware('can:clientes.ver');

    // ── Garantías ─────────────────────────────────────────────
    Route::get('/garantias',           [App\Http\Controllers\GarantiaController::class, 'index'])->name('admin.garantias.index')->middleware('can:garantias.ver');
    Route::get('/garantias/{id}',      [App\Http\Controllers\GarantiaController::class, 'show'])->name('admin.garantias.show')->middleware('can:garantias.ver');
    Route::put('/garantias/{id}/usar', [App\Http\Controllers\GarantiaController::class, 'marcarUsada'])->name('admin.garantias.usar')->middleware('can:garantias.usar');

    // ── Ingresos ──────────────────────────────────────────────
    Route::get('/ingresos', [App\Http\Controllers\IngresoController::class, 'index'])->name('admin.ingresos.index')->middleware('can:ingresos.ver');

    // ── Reportes ──────────────────────────────────────────────
    Route::get('/reportes',       [App\Http\Controllers\ReporteController::class, 'index'])->name('admin.reportes.index')->middleware('can:reportes.ver');
    Route::get('/reportes/pdf',   [App\Http\Controllers\ReporteController::class, 'pdf'])->name('admin.reportes.pdf')->middleware('can:reportes.pdf');
    Route::get('/reportes/excel', [App\Http\Controllers\ReporteController::class, 'excel'])->name('admin.reportes.excel')->middleware('can:reportes.excel');

    // ── Usuarios ──────────────────────────────────────────────
    Route::get('/usuarios',              [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware('can:usuarios.ver');
    Route::get('/usuarios/create',       [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('can:usuarios.crear');
    Route::post('/usuarios/create',      [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('can:usuarios.crear');
    Route::get('/usuario/{id}',          [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('can:usuarios.ver');
    Route::get('/usuario/{id}/edit',     [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('can:usuarios.editar');
    Route::put('/usuario/{id}',          [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('can:usuarios.editar');
    Route::delete('/usuario/{id}',       [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('can:usuarios.toggle');
    Route::patch('/usuarios/{id}/toggle', [App\Http\Controllers\UsuarioController::class, 'toggle'])->name('admin.usuarios.toggle')->middleware('can:usuarios.toggle');

    // ── Roles ─────────────────────────────────────────────────
    Route::get('/roles',                [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('can:roles.ver');
    Route::get('/roles/create',         [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('can:roles.crear');
    Route::post('/roles/create',        [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('can:roles.crear');
    Route::get('/rol/{id}',             [App\Http\Controllers\RoleController::class, 'show'])->name('admin.roles.show')->middleware('can:roles.ver');
    Route::get('/rol/{id}/edit',        [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('can:roles.editar');
    Route::put('/rol/{id}',             [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('can:roles.editar');
    Route::delete('/rol/{id}',          [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('can:roles.eliminar');
    Route::get('/rol/{id}/permisos',    [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('can:roles.permisos');
    Route::put('/rol/{id}/permisos',    [App\Http\Controllers\RoleController::class, 'permisosUpdate'])->name('admin.roles.permisos.update')->middleware('can:roles.permisos');

    // ── Tipos de Equipo ───────────────────────────────────────
    Route::get('/tipos-equipo',          [App\Http\Controllers\TipoEquipoController::class, 'index'])->name('tipo_equipos.index')->middleware('can:tipos_equipo.ver');
    Route::post('/tipos-equipo',         [App\Http\Controllers\TipoEquipoController::class, 'store'])->name('tipo_equipos.store')->middleware('can:tipos_equipo.crear');
    Route::get('/tipos-equipo/{id}/edit', [App\Http\Controllers\TipoEquipoController::class, 'edit'])->name('tipo_equipos.edit')->middleware('can:tipos_equipo.editar');
    Route::put('/tipos-equipo/{id}',     [App\Http\Controllers\TipoEquipoController::class, 'update'])->name('tipo_equipos.update')->middleware('can:tipos_equipo.editar');
    Route::delete('/tipos-equipo/{id}',  [App\Http\Controllers\TipoEquipoController::class, 'destroy'])->name('tipo_equipos.destroy')->middleware('can:tipos_equipo.eliminar');

    // ── Catálogo de Servicios ─────────────────────────────────
    Route::get('/servicios',          [App\Http\Controllers\CatalogoServicioController::class, 'index'])->name('catalogo_servicios.index')->middleware('can:servicios.ver');
    Route::post('/servicios',         [App\Http\Controllers\CatalogoServicioController::class, 'store'])->name('catalogo_servicios.store')->middleware('can:servicios.crear');
    Route::get('/servicios/{id}/edit', [App\Http\Controllers\CatalogoServicioController::class, 'edit'])->name('catalogo_servicios.edit')->middleware('can:servicios.editar');
    Route::put('/servicios/{id}',     [App\Http\Controllers\CatalogoServicioController::class, 'update'])->name('catalogo_servicios.update')->middleware('can:servicios.editar');
    Route::delete('/servicios/{id}',  [App\Http\Controllers\CatalogoServicioController::class, 'destroy'])->name('catalogo_servicios.destroy')->middleware('can:servicios.eliminar');
});
