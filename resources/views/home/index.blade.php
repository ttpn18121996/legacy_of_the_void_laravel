@extends('layouts.app')

@use('App\Enums\PathType')

@section('title', 'Home')

@section('content')
    <div class="main__header">
        <x-search-form action="{{ route('videos.index') }}" />
        <div class="statistical">
            <div class="statistical-item">
                <div class="card">
                    <div class="card__title space-x-2">
                        <div>
                            <h3>{{ $totalUnapprovedVideos }}</h3>
                            <div>Total unapproved</div>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-lg icon-success">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="card__body">
                        <a href="{{ route('list-view.index', ['path' => PathType::REVIEW->value]) }}">More information</a>
                    </div>
                </div>
            </div>
            <div class="statistical-item">
                <div class="card">
                    <div class="card__title space-x-2">
                        <div>
                            <h3>{{ $totalUnpublishedVideos }}</h3>
                            <div>Total unpublished</div>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-lg icon-warning">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </div>
                    </div>
                    <div class="card__body">
                        <a href="{{ route('list-view.index', ['path' => PathType::APPROVED->value]) }}">More information</a>
                    </div>
                </div>
            </div>
            <div class="statistical-item">
                <div class="card">
                    <div class="card__title space-x-2">
                        <div>
                            <h3>{{ $totalVideos }}</h3>
                            <div>Total videos</div>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-lg icon-info">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="card__body">
                        <a href="{{ route('videos.index') }}">More information</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main__body">
        <h3 class="my-4">Filter</h3>
        <x-filter-panel :tags="$tags" />
    </div>
@endsection
