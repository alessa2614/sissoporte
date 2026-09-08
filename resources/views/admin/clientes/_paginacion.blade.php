@if ($clientes->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4 px-3">
        <div class="text-muted">
            Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }}
            de {{ $clientes->total() }} registros
        </div>
        <div>
            {{ $clientes->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endif
