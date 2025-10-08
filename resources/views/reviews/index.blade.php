@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="main__header">
        <div class="filter-wrapper space-y-4">
            <div class="form-search" id="reviews-search">
                <div class="form-search__box">
                    <input type="search" name="q" placeholder="Search..." />
                </div>
            </div>
        </div>
        @error('video')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror
    </div>
    <div class="main__body">
        <div class="data--list">
            @foreach($videos as $index => $video)
                <div class="data__item--list" data-title="{{ $video->title }}">
                    <a class="data__item--link" href="{{  route('reviews.show', ['title' => $video->title, 'path' => $video->path]) }}">
                        #{{ $index + 1 }} - {{ $video->title }}
                    </a>
                    <form class="data__item--button" action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="title" value="{{ $video->title }}">
                        @if ($video->path === 'reviews')
                            <input type="hidden" name="path" value="reviews">
                            <button class="data__state--list state--success">Approve</button>
                        @else
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
    <script src="{{ asset('static/js/review-index.js?v='.time()) }}"></script>
    <script>
        reviewIndex();
        lotv.useScrollToTop();
    </script>
@endpush
