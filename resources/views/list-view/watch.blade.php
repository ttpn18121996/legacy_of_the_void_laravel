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
                <form action="{{ route('list-view.store') }}" method="POST" class="space-x-2">
                    @csrf
                    <input type="hidden" name="title" value="{{ $title }}">
                    <input type="hidden" name="path" value="{{ $path }}">
                    @switch($path)
                        @case(PathType::REVIEW->value)
                            <button type="submit" class="video__actions--success">
                                Aprove
                            </button>
                            @break
                        @case(PathType::APPROVED->value)
                            <button type="submit" class="video__actions--warning">
                                Reject
                            </button>
                            <button type="button" class="video__actions--info" id="publish-video">
                                Publish
                            </button>
                            @break
                        @case(PathType::TRASH->value)
                            <button type="submit" class="video__actions--success">
                                Restore
                            </button>
                            @break
                        @default
                    @endswitch
                </form>
            @endif
            @switch($path)
                @case(PathType::REVIEW->value)
                @case(PathType::APPROVED->value)
                    <form action="{{ route('list-view.destroy', ['title' => $title, 'path' => $path]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="video__actions--danger" type="submit">
                            Move to trash
                        </button>
                    </form>
                    @break
                @case(PathType::TRASH->value)
                    <form action="{{ route('list-view.destroy', ['title' => $title, 'path' => $path, 'permanent' => '1']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="video__actions--danger" type="submit">
                            Permanent Delete
                        </button>
                    </form>
                    @break
                @default
            @endswitch
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/list-view-watch.js?v='.time()) }}"></script>
    <script>
        listViewWatch({
            publishUrl: "{{ route('list-view.update') }}",
            videoTitle: "{!! $title !!}",
        });
    </script>
@endpush
