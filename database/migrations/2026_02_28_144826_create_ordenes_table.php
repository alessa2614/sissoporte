<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('restrict');
            $table->foreignId('tipo_equipo_id')->constrained('tipo_equipos')->onDelete('restrict');
            $table->string('marca', 80)->nullable();
            $table->string('modelo', 80)->nullable();
            $table->text('descripcion');
            $table->string('foto', 255)->nullable();
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('estado', [
                'recibido',
                'en_revision',
                'esperando_aprobacion',
                'en_reparacion',
                'listo',
                'entregado'
            ])->default('recibido');
            $table->decimal('costo_estimado', 8, 2)->nullable();
            $table->decimal('total_final', 8, 2)->nullable();
            $table->enum('estado_pago', ['pendiente', 'pagado'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
