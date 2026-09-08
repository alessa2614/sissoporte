@forelse ($ordenes as $orden)
    <tr>
        <td><strong>{{ $orden->codigo }}</strong></td>
        <td>{{ $orden->cliente->nombre }}</td>
        <td>
            {{ $orden->tipoEquipo->nombre }}
            @if ($orden->marca)
                <br><small class="text-muted">{{ $orden->marca }} {{ $orden->modelo }}</small>
            @endif
        </td>
        <td>{{ $orden->tecnico->name ?? '—' }}</td>
        <td>{!! $orden->badgeEstado() !!}</td>
        <td>
            @if ($orden->estado_pago == 'pagado')
                <span class="badge bg-success">Pagado</span>
            @else
                <span class="badge bg-warning text-dark">Pendiente</span>
            @endif
        </td>
        <td>{{ $orden->created_at->format('d/m/Y') }}</td>
        <td class="text-center">
            <a href="{{ route('admin.ordenes.show', $orden->id) }}" class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('admin.ordenes.edit', $orden->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('admin.ordenes.destroy', $orden->id) }}" method="POST"
                style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm btn-eliminar">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center text-muted py-4">
            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
            No se encontraron órdenes con ese criterio.
        </td>
    </tr>
@endforelse
