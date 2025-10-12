@extends('layouts.app')

@section('title', $actress->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/videos-index.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/actresses-show.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/modal.css?v='.time()) }}">
@endpush

@section('content')
    <div class="main__header">
        <x-search-form />
    </div>
    <div class="main__body">
        <div class="actress-bio">
            <div class="actress-bio__thumbnail">
                <img src="{{ $actress->public_path }}" alt="{{ $actress->name }}">
            </div>
            <div class="actress-bio__info">
                <h3>
                    {{ $actress->name }}
                    @if ($actress->name != $actress->another_name)
                        <br>&#40;{{ $actress->another_name }}&#41;
                    @endif
                </h3>
                <div class="actress-bio__section my-4">
                    <p class="actress-bio__section-title">Tags:</p>
                    <div class="actress-bio__section-body space-x-2">
                        @foreach($actress->tags as $tag)
                            <span class="tag__button">{{ $tag->title }}</span>
                        @endforeach
                        <button class="tag__button toggle-modal" data-target="#tags-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="data--grid">
            @forelse($videos as $video)
                <x-video-item :video="$video" />
            @empty
            @endforelse
        </div>

        {{ $videos->links('vendor.pagination') }}
    </div>
@endsection

@section('other')
    <div id="tags-modal">
        <x-modal title="Tags">
            <x-slot name="body">
                <x-selection-list name="tags" :items="$tags" :selected-items="$selectedTags" />
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn--primary" id="update-tags">Save</button>
            </x-slot>
        </x-modal>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/actresses-show.js?v='.time()) }}"></script>
    <script>
        actressesShow({
            actressId: {{ $actress->id }},
            updateTagsUrl: "{{ route('actresses.update-tags') }}",
        });
        lotv.dispatchVideoThumbnail();
    </script>
@endpush
