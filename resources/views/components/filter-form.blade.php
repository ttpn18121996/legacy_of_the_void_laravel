<form id="filter-content" class="filter-content" method="GET" action="{{ route('videos.index') }}">
    <div class="form-input__checkbox">
        @foreach($tags as $tag)
            <div class="form-input__checkbox-item">
                <input
                    id="tag-{{ $tag->id }}"
                    type="checkbox"
                    name="tags[]"
                    value="{{ $tag->slug }}" {{ in_array($tag->slug, $filteredTags->pluck('slug')->toArray()) ? 'checked' : '' }}
                />
                <label for="tag-{{ $tag->id }}">{{ $tag->title_for_human }}</label>
            </div>
        @endforeach
    </div>
    <div class="form-button mt-4">
        <button type="submit" class="btn--sm btn--primary">Submit</button>
    </div>
</form>
