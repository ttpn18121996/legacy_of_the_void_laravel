@extends('layouts.app')

@use('App\Enums\PathType')

@section('title', $title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/videos-show.css?v='.time()) }}">
    <link rel="stylesheet" href="{{ asset('static/css/modal.css?v='.time()) }}">
@endpush

@section('content')
    <div class="main__header">
        <x-search-form />
    </div>
    <div class="main__body">
        <div class="video-wrapper">
            <div class="video">
                <x-player :title="$title" :path="$path" />
            </div>
        </div>
        <div class="video__actions space-x-2">
            @if (in_array($path, PathType::reviewable()))
                <form action="{{ route('list-view.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="title" value="{{ $title }}">
                    <input type="hidden" name="path" value="{{ $path }}">
                    <button class="{{ $path === PathType::REVIEW->value ? 'video__actions--success' : 'video__actions--warning' }}">
                        {{ $path === PathType::REVIEW->value ? 'Aprove' : 'Reject' }}
                    </button>
                </form>
            @endif
            @if ($path === PathType::APPROVED->value)
                <button class="video__actions--info" id="publish-video" type="submit">
                    Publish
                </button>
            @endif
            <form action="{{ route('reviews.destroy', ['title' => $title, 'path' => $path]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="video__actions--danger" type="submit">
                    Move to trash
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/list-view-watch.js?v='.time()) }}"></script>
    <script>
        listViewWatch({
            publishUrl: "{{ route('reviews.update') }}",
            videoTitle: "{!! $title !!}",
        });
    </script>
@endpush
