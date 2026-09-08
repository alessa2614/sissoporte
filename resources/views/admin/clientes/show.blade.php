@extends('layouts.admin')

@section('content')
    <div class="page-heading">
        <h3>Detalle del Cliente</h3>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Información del cliente</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:35%">Nombre:</th>
                            <td>{{ $cliente->nombre }}</td>
                        </tr>
                        <tr>
                            <th>Celular:</th>
                            <td>
                                {{ $cliente->celular }}
                                {{-- Link WhatsApp --}}
                                <a href="https://wa.me/51{{ $cliente->celular }}" target="_blank"
                                    class="btn btn-success btn-sm ms-2">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Correo:</th>
                            <td>{{ $cliente->correo ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Registrado:</th>
                            <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                </div>
            </div>
        </div>

        {{-- Historial de órdenes -- se completará cuando hagamos órdenes --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Historial de órdenes</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted text-center">
                        <i class="bi bi-clock-history fs-3"></i><br>
                        El historial estará disponible cuando se registren órdenes.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
