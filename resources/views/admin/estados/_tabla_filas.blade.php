@forelse ($ordenes as $orden)
    <tr>
        <td>
            <a href="{{ route('admin.ordenes.show', $orden->id) }}">
                <strong>{{ $orden->codigo }}</strong>
            </a>
        </td>
        <td>
            {{ $orden->cliente->nombre }}<br>
            <small class="text-muted">{{ $orden->cliente->celular }}</small>
        </td>
        <td>
            {{ $orden->tipoEquipo->nombre }}
            @if ($orden->marca)
                <br><small class="text-muted">{{ $orden->marca }}</small>
            @endif
        </td>
        <td>{{ $orden->tecnico->name ?? '—' }}</td>
        <td>{!! $orden->badgeEstado() !!}</td>
        <td class="text-center">
            <button class="btn btn-primary btn-sm"
                onclick="abrirModal(
                    {{ $orden->id }},
                    '{{ $orden->codigo }}',
                    '{{ $orden->estado }}'
                )">
                <i class="bi bi-arrow-repeat"></i> Cambiar
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
            No se encontraron órdenes con ese criterio.
        </td>
    </tr>
@endforelse
