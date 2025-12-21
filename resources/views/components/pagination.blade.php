@props([
    'data' => null,
])

@if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->hasPages())
    {{ $data->links('vendor.pagination') }}
@elseif ($data instanceof \Illuminate\Pagination\AbstractPaginator && $data->hasPages())
    {{ $data->links('vendor.pagination') }}
@endif
