@extends('layouts.app')

@section('title', 'Edit video')

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/videos-index.css') }}">
@endpush

@section('content')
    <div class="main__header">
        @error('video')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="main__body">
        <form action="{{ route('admin.videos.update', $video) }}" method="POST" class="form">
            @csrf
            @method('PUT')

            <div class="form-input">
                <label for="title" class="required">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title', $video->title) }}" placeholder="Title" autocomplete="off" autofocus />
                @error('title')
                    <label for="title" class="text-error">{{ $message }}</label>
                @enderror
            </div>

            <div class="form-input">
                <label for="tags">Actresses</label>
                <x-selection-list name="actresses" :items="$actresses" :selected-items="$selectedActresses" />
            </div>
            
            <div class="form-input">
                <label for="tags">Tags</label>
                <x-selection-list name="tags" :items="$tags" :selected-items="$selectedTags" />
            </div>

            <div class="data--grid space-x-2">
                @foreach($video->thumbnails as $index => $thumbnail)
                    <div class="table__thumbnails-wrapper mb-2">
                        <div class="video-card__thumbnails">
                            <img
                                id="video-thumbnail-{{ $video->id }}"
                                src="{{ $thumbnail->public_path }}"
                                class="show"
                            />
                        </div>
                        <input type="radio" name="default_thumbnail" value="{{ $index + 1 }}" {{ $thumbnail->is_default ? 'checked' : '' }} />
                    </div>
                @endforeach
            </div>

            <div class="form-button space-x-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-secondary" href="{{  route('admin.videos.index') }}">Back to list</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        lotv.useSelectionList();
    </script>
@endpush
