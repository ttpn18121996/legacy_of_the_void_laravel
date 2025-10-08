@extends('layouts.app')

@section('title', 'Watch')

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
                <x-player :title="$video->title" :thumbnail="$video->thumbnail->public_path" path="videos" />

                <div class="video__relationship">
                    <div class="relationship__section">
                        <div class="section__title">Actresses:</div>
                        <div class="section__body space-x-2">
                            @foreach($video->actresses as $actress)
                                <a class="tag__button" href="{{ route('actresses.show', $actress) }}">{{ $actress->name }}</a>
                            @endforeach
                            <button type="button" class="tag__button toggle-modal" data-target="#actresses-modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="relationship__section">
                        <div class="section__title">Categories:</div>
                        <div class="section__body space-x-2">
                            @foreach($video->categories as $category)
                                <a class="tag__button" href="{{ route('categories.show', ['slug' => $category->slug]) }}">{{ $category->title }}</a>
                            @endforeach
                            <button type="button" class="tag__button toggle-modal" data-target="#categories-modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="relationship__section">
                        <div class="section__title">Tags:</div>
                        <div class="section__body space-x-2">
                            @foreach($video->tags as $tag)
                                <a class="tag__button" href="{{  get_filter_tag_url($tag->slug) }}">&#35;{{ $tag->title }}</a>
                            @endforeach
                            <button type="button" class="tag__button toggle-modal" data-target="#tags-modal">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="relationship__section">
                        <div class="section__title">Previews:</div>
                        <div class="section__body space-x-2">
                            <div class="section__body-preview space-x-2">
                                @forelse($video->thumbnails as $thumbnail)
                                    <img src="{{ $thumbnail->public_path }}" />
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('other')
    <div id="actresses-modal">
        <x-modal title="Actresses">
            <x-slot name="body">
                <x-selection-list name="actresses" />
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-primary" id="update-actresses">Save</button>
            </x-slot>
        </x-modal>
    </div>
    <div id="categories-modal">
        <x-modal title="Categories">
            <x-slot name="body">
                <x-selection-list name="categories" :items="$categories" :selected-items="$selectedCategories" />
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-primary" id="update-categories">Save</button>
            </x-slot>
        </x-modal>
    </div>
    <div id="tags-modal">
        <x-modal title="Tags">
            <x-slot name="body">
                <x-selection-list name="tags" />
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-primary" id="update-tags">Save</button>
            </x-slot>
        </x-modal>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/videos-show.js?v='.time()) }}"></script>
    <script>
        videosShow({
            updateActressesUrl: "{{ route('videos.update-actresses') }}",
            updateCategoriesUrl: "{{ route('videos.update-categories') }}",
            updateTagsUrl: "{{ route('videos.update-tags') }}",
            getActressesOptionsUrl: "{{ route('options.get-actresses', ['video_id' => $video->id]) }}",
            getTagsOptionsUrl: "{{ route('options.get-tags', ['video_id' => $video->id]) }}",
            videoId: {{ $video->id }},
        });
    </script>
@endpush
