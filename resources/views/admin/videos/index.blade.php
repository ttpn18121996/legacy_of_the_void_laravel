@extends('layouts.app')

@section('title', 'Video management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/toast.css?v='.time()) }}">
    <link rel="stylesheet" href="{{ asset('static/css/videos-index.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/admin.css') }}">
@endpush

@section('content')
    @php
        $q = request()->query('q');
    @endphp

    <div class="main__header">
        <x-search-form />
    </div>
    <div class="main__body">
        <div class="flexible-table">
            @forelse($videos as $video)
                <div class="flexible-table__row">
                    <div class="flexible-table__cell-header">
                        <div class="flexible-table__cell-thumbnail">
                            <a href="{{ route('videos.show', $video) }}" title="{{ $video->title }}">
                                <div class="video-card__thumbnails">
                                    <img
                                        id="video-thumbnail-{{ $video->id }}"
                                        class="show"
                                        loading="lazy"
                                    />
                                </div>
                            </a>
                            <div class="video-card__thumbnails-control space-x-2" data-target="#video-thumbnail-{{ $video->id }}">
                                @forelse($video->thumbnails as $thumbnail)
                                    <button
                                        type="button"
                                        data-target="#video-thumbnail-{{ $video->id }}"
                                        data-src="{{ $thumbnail->public_path }}"
                                        data-is-default="{{ $thumbnail->is_default }}"
                                        class="{{ $thumbnail->is_default ? 'active' : '' }}"
                                    >
                                        <span class="sr-only">&nbsp;</span>
                                    </button>
                                @empty
                                    <img src="{{ asset('static/imgs/img-1920x1080.png') }}" class="show" />
                                @endforelse
                            </div>
                        </div>
                        <div class="flexible-table__cell-item--inline">
                            <p>{{ $video->duration }}</p>
                            <p>{{ $video->dimensions }}</p>
                        </div>
                    </div>
                    <div class="flexible-table__cell-body">
                        <div class="flexible-table__cell-item">#{{ $video->id }} (Like: {{ number_format($video->like) }})</div>
                        <div class="flexible-table__cell-item">
                            <p class="mb-4">
                                <a href="{{ route('videos.show', ['id' => $video->id]) }}">{!! $video->highlightTitle($q) !!}</a>
                            </p>
                            <div class="table__tags space-x-2">
                                @foreach($video->tags as $tag)
                                    <a href="{{ get_filter_tag_url($tag->slug) }}">{{ $tag->title_for_human }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flexible-table__cell-footer">
                        <a href="{{ route('admin.videos.edit', ['id' => $video->id]) }}" class="btn--sm btn--primary">Edit</a>
                        <button
                            data-id="{{ $video->id }}"
                            data-url="{{ route('admin.videos.sync-tags', ['id' => $video->id]) }}"
                            class="btn--sm btn--info btn-sync-tags"
                        >
                            Sync tags
                        </button>
                        <button
                            data-id="{{ $video->id }}"
                            data-url="{{ route('admin.videos.destroy', ['id' => $video->id]) }}"
                            class="btn--sm btn--danger btn-delete"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="flexible-table__row">
                    <div class="flexible-table__cell-body">
                        <div class="flexible-table__cell-item">
                            No videos found.
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{ $videos->links('vendor.pagination') }}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/confirm-dialog.js?v='.time()) }}"></script>
    <script src="{{ asset('static/js/admin/videos-index.js?v='.time()) }}"></script>
    <script>
        videosIndex();
        lotv.dispatchVideoThumbnail();
    </script>
@endpush
