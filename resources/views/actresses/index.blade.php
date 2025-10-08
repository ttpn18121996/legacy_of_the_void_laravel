@extends('layouts.app')

@section('title', 'Actresses')

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/actresses-index.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/pagination.css') }}">
@endpush

@section('content')
    <div class="main__header">
        <x-search-form :filteredTags="$filteredTags" />
    </div>
    <div class="main__body">
        <div class="data--grid">
            @forelse($actresses as $actress)
                <x-actress-item :actress="$actress" />
            @empty
            @endforelse
        </div>

        {{ $actresses->links('vendor.pagination') }}
    </div>
@endsection
