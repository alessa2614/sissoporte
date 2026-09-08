@forelse ($garantias as $g)
    <tr>
        <td>
            <a href="{{ route('admin.ordenes.show', $g->orden->id) }}">
                <strong>{{ $g->orden->codigo }}</strong>
            </a>
        </td>
        <td>{{ $g->orden->cliente->nombre }}</td>
        <td>
            {{ $g->orden->tipoEquipo->nombre }}
            @if ($g->orden->marca)
                — {{ $g->orden->marca }}
            @endif
        </td>
        <td>{{ $g->fecha_inicio->format('d/m/Y') }}</td>
        <td>{{ $g->fecha_fin->format('d/m/Y') }}</td>
        <td>
            @php $dias = (int) $g->diasRestantes(); @endphp

        <td>
            @if ($g->estado === 'vigente' && $dias > 0)
                <span class="badge {{ $dias <= 5 ? 'bg-warning text-dark' : 'bg-success' }}">
                    {{ $dias }} días
                </span>
            @elseif ($g->estado === 'vigente' && $dias <= 0)
                <span class="badge bg-danger">
                    <i class="bi bi-x-circle"></i> Vencido
                </span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>

        <td>
            @if ($g->estado === 'vigente')
                <span class="badge bg-success">
                    <i class="bi bi-check-circle"></i> Vigente
                </span>
            @elseif ($g->estado === 'vencida')
                <span class="badge bg-secondary">
                    <i class="bi bi-clock-history"></i> Vencida
                </span>
            @else
                <span class="badge bg-danger">
                    <i class="bi bi-shield-x"></i> Usada
                </span>
            @endif
        </td>
        <td class="text-center">
            <a href="{{ route('admin.garantias.show', $g->id) }}" class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Ver
            </a>
            @if ($g->estado === 'vigente')
                <button class="btn btn-warning btn-sm btn-marcar-usada" data-id="{{ $g->id }}">
                    <i class="bi bi-shield-x"></i> Marcar usada
                </button>
                <form id="form_usar_{{ $g->id }}" action="{{ route('admin.garantias.usar', $g->id) }}"
                    method="POST" style="display:none;">
                    @csrf @method('PUT')
                </form>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center text-muted">
            No hay garantías registradas aún.
        </td>
    </tr>
@endforelse
