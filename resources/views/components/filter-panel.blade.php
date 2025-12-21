@props([
    'tags' => [],
    'searchType' => \App\Enums\SearchType::VIDEO->value,
    'selectedTags' => [],
    'keyword' => '',
])

@use('App\Enums\SearchType')

<form action="{{ route('search') }}" method="GET">
    <div class="card">
        <div class="card__body">
            <div class="form-input">
                <label>Keyword</label>
                <div class="form-input__group">
                    <input id="keyword" type="text" name="keyword" placeholder="Actress name or Video title" value="{{ $keyword }}" />
                </div>
            </div>
            <div class="form-input">
                <label>Search type</label>
                <div class="form-input__group">
                    <select name="search_type">
                        @foreach (SearchType::cases() as $type)
                            <option value="{{ $type->value }}" {{ $searchType === $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-input">
                <label>Tags</label>
                <div class="form-input__checkbox">
                    @foreach($tags as $tag)
                        <div class="form-input__checkbox-item">
                            <input
                                id="tag-{{ $tag->id }}"
                                type="checkbox"
                                name="tags[]"
                                value="{{ $tag->slug }}"
                                {{ in_array($tag->slug, $selectedTags) ? 'checked' : '' }}
                            />
                            <label for="tag-{{ $tag->id }}">{{ $tag->title }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-button">
                <button type="submit" class="btn btn--primary">Search</button>
            </div>
        </div>
    </div>
</form>
