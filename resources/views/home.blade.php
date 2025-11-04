@extends('layouts.app')

@use('App\Enums\PathType')
@use('App\Enums\SearchType')

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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-lg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M12 18.75H4.5a2.25 2.25 0 0 1-2.25-2.25V9m12.841 9.091L16.5 19.5m-1.409-1.409c.407-.407.659-.97.659-1.591v-9a2.25 2.25 0 0 0-2.25-2.25h-9c-.621 0-1.184.252-1.591.659m12.182 12.182L2.909 5.909M1.5 4.5l1.409 1.409" />
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-lg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-lg">
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
        <form action="">
            <div class="card">
                <div class="card__body">
                    <div class="form-input">
                        <label>Keyword</label>
                        <div class="form-input__group">
                            <input id="keyword" type="text" name="keyword" placeholder="Actress name or Video title" />
                        </div>
                    </div>
                    <div class="form-input">
                        <label>Search type</label>
                        <div class="form-input__group">
                            <select name="search_type">
                                @foreach (SearchType::cases() as $type)
                                    <option value="{{ $type->value }}" {{ request('search_type') == $type->value ? 'selected' : '' }}>
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
                                        value="{{ $tag->id }}"
                                        {{ in_array($tag->id, request()->input('tags', [])) ? 'checked' : '' }}
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
    </div>
@endsection
