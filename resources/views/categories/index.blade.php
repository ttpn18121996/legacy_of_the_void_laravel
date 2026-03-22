@extends('layouts.app')

@section('title', $title ?? 'Categories')

@section('content')
    <div class="main__header">
        <x-search-form :filteredTags="$filteredTags ?? null" />
    </div>
    <div class="main__body">
        <div class="data--list">
            @forelse($categories as $category)
                <div class="data__item--list quick-search-item" data-title="{{ $category->title }}">
                    <div class="data__item--link">
                        <a href="{{ route('categories.show', $category->slug) }}" class="block mb-4">
                            #{{ $category->id }} | {{ $category->title }}
                        </a>
                        <div class="table__tags space-x-2">
                            @forelse($category->tags as $tag)
                                <a href="{{ get_filter_tag_url($tag->slug) }}">{{ $tag->title_for_human }}</a>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="data__item--list">
                    <p>No categories found.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        lotv.useScrollToTop();
    </script>
@endpush
