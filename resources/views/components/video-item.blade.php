@props(['video' => null])

@isset($video)
    <div class="data__item--grid">
        <div class="data__card--grid">
            <a href="{{ route('videos.show', $video) }}" title="{{ $video->title }}">
                <div class="video-card__thumbnails">
                    <img
                        id="video-thumbnail-{{ $video->id }}"
                        class="show"
                        loading="lazy"
                    />
                </div>
            </a>

            <div class="video-card__thumbnails-control space-x-2" data-target="#video-thumbnail-{{ $video->id }}">
                @forelse($video->thumbnails as $thumbnail)
                    <button
                        type="button"
                        data-target="#video-thumbnail-{{ $video->id }}"
                        data-src="{{ $thumbnail->public_path }}"
                        data-is-default="{{ $thumbnail->is_default }}"
                        class="{{ $thumbnail->is_default ? 'active' : '' }}"
                    >
                        <span class="sr-only">&nbsp;</span>
                    </button>
                @empty
                    <img src="{{ asset('static/imgs/img-1920x1080.png') }}" class="show" />
                @endforelse
            </div>

            <a href="{{ route('videos.show', $video) }}" title="{{ $video->title }}">
                <p class="video-card__title">{!! $video->highlightTitle(request()->query('q')) !!}</p>
                <div class="video-card__detail">
                    <div class="{{ $video->isHD() ? 'hd' : '' }}">{{ $video->quality }}</div>
                    <div>{{ $video->duration }}</div>
                </div>
            </a>
        </div>
    </div>
@endisset
