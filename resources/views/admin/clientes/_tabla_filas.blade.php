@forelse ($clientes as $index => $cliente)
    <tr>
        <td>{{ ($clientes->currentPage() - 1) * $clientes->perPage() + $index + 1 }}</td>
        <td>{{ $cliente->nombre }}</td>
        <td>{{ $cliente->celular }}</td>
        <td>{{ $cliente->correo ?? '—' }}</td>
        <td class="text-center">
            <a href="{{ route('admin.clientes.show', $cliente->id) }}" class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Ver
            </a>
            <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <form action="{{ route('admin.clientes.destroy', $cliente->id) }}" method="POST"
                style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm btn-eliminar">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </form>
            <a href="{{ route('admin.clientes.historial', $cliente->id) }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-clock-history"></i> Historial
            </a>
        </td>
    </tr>
@empty
@endforelse
