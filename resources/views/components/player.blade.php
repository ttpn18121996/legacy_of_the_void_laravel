@props([
    'thumbnail' => null,
    'title' => '',
    'path' => 'videos',
])

<div>
    <div class="video__player">
        <video
            id="video-player"
            controls
            preload="auto"
            @isset($thumbnail) poster="{{ $thumbnail }}" @endif
        >
            <source src="{{ route('videos.stream', ['file_name' => $title, 'path' => $path]) }}" type="video/mp4" />
        </video>
    </div>
    <div class="video__title my-4">{{ $title }}</div>
</div>
