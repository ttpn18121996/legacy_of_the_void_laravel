@extends('layouts.app')

@use('App\Enums\PathType')

@section('title', $title)

@section('content')
    <div class="main__header">
        <x-search-form :filteredTags="$filteredTags" />
        @error('video')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror
    </div>
    <div class="main__body">
        <div class="data--list">
            @foreach($videos as $index => $video)
                <div class="data__item--list quick-search-item" data-title="{{ $video->title }}">
                    <a class="data__item--link" href="{{  route('list-view.show', ['title' => $video->title, 'path' => $video->path]) }}">
                        #{{ $index + 1 }} - {{ $video->title }}
                    </a>
                    <form class="data__item--button" action="{{ route('list-view.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="title" value="{{ $video->title }}">
                        @if ($video->path === PathType::REVIEW->value)
                            <input type="hidden" name="path" value="reviews">
                            <button class="data__state--list state--success">Approve</button>
                        @elseif ($video->path === PathType::APPROVED->value)
                            <input type="hidden" name="path" value="approved">
                            <button class="data__state--list state--warning">Reject</button>
                        @endif
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        lotv.useScrollToTop();
    </script>
@endpush
