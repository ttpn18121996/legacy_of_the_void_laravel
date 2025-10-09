@props([
    'filteredTags' => null,
    'action' => '',
])

<div class="filter-wrapper space-y-4">
    <form action="{{ $action }}" method="GET" class="form-search" id="form-search">
        {!! fill_input_to_sort() !!}
        <div class="form-search__box">
            <input type="search" name="q" value="{{ request()->query('q') }}" placeholder="Search..." autocomplete="off" />
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </div>
    </form>
    @isset($filteredTags)
        <div class="filtered-tags space-x-2">
            @foreach($filteredTags as $tag)
                <a class="tag__button" href="{{ get_filter_tag_url($tag->slug, false) }}">&#35;{{ $tag->title }}</a>
            @endforeach
        </div>
    @endisset
</div>
