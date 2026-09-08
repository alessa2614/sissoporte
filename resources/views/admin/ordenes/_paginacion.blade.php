@if ($ordenes->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4 px-3">
        <div class="text-muted">
            Mostrando {{ $ordenes->firstItem() }} a {{ $ordenes->lastItem() }}
            de {{ $ordenes->total() }} registros
        </div>
        <div>{{ $ordenes->links('pagination::bootstrap-4') }}</div>
    </div>
@endif
