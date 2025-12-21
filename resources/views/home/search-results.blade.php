@extends('layouts.app')

@use('App\Enums\SearchType')

@section('title', 'Search results')

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/videos-index.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/actresses-index.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/pagination.css') }}">
@endpush

@section('content')
    <div class="main__body">
        <h3 class="my-4">Filter</h3>
        <x-filter-panel :tags="$tags" :searchType="$searchType" :selectedTags="$selectedTags" :keyword="$keyword" />
        <h3 class="my-4">Search results</h3>
        @if ($results->isEmpty())
            <div>No results found.</div>
        @else
            <div class="data--grid">
                @forelse($results as $item)
                    @if ($searchType === SearchType::ACTRESS->value)
                        <x-actress-item :actress="$item" />
                    @else
                        <x-video-item :video="$item" />
                    @endif
                @empty
                @endforelse
            </div>

            <x-pagination :data="$results" />
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        lotv.dispatchVideoThumbnail();
    </script>
@endpush
