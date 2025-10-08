@extends('layouts.app')

@section('title', 'Videos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/videos-index.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/pagination.css') }}">
@endpush

@section('content')
    <div class="main__header">
        <x-search-form :filteredTags="$filteredTags" />
    </div>
    <div class="main__body">
        <div class="data--grid">
            @forelse($videos as $video)
                <x-video-item :video="$video" />
            @empty
            @endforelse
        </div>

        {{ $videos->links('vendor.pagination') }}
    </div>
@endsection

@push('scripts')
    <script>
        lotv.dispatchVideoThumbnail();
    </script>
@endpush
