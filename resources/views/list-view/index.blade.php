@extends('layouts.app')

@use('App\Enums\PathType')

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
    <script src="{{ asset('static/js/list-view-index.js?v='.time()) }}"></script>
    <script>
        listViewIndex();
        lotv.useScrollToTop();
    </script>
@endpush
