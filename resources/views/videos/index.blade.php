@extends('layouts.app')

@use('App\Enums\SortDestination');
@use('App\Enums\VideoSort');

@section('title', 'Videos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/videos-index.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/pagination.css') }}">
@endpush

@section('content')
    <div class="main__header">
        <x-search-form :filteredTags="$filteredTags" />
    </div>
    <div class="main__body">
        <div class="data__controls">
            <form action="" method="GET" class="space-x-4">
                {!! fill_input_to_sort() !!}
                <div class="data__control-item space-x-2">
                    <div>Sort:</div>
                    <select name="sort_by">
                        @foreach(VideoSort::labels() as $value => $label)
                            <option value="{{ $value }}" {{ request()->query('sort_by', VideoSort::RECENTLY_ADDED->value) === $value ? 'selected' : '' }}>{{  $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="data__control-item space-x-2">
                    <div>Destination:</div>
                    <select name="destination">
                        @foreach(SortDestination::labels() as $value => $label)
                            <option value="{{ $value }}" {{ request()->query('destination', SortDestination::DESCENDING->value) === $value ? 'selected' : '' }}>{{  $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn-sm btn-primary">Apply</button>
            </form>
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

@push('scripts')
    <script>
        lotv.dispatchVideoThumbnail();
    </script>
@endpush
