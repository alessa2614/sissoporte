@if ($garantias->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4 px-3">
        <div class="text-muted">
            Mostrando {{ $garantias->firstItem() }} a {{ $garantias->lastItem() }}
            de {{ $garantias->total() }} registros
        </div>
        <div>
            {{ $garantias->appends(['q' => request('q')])->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endif
