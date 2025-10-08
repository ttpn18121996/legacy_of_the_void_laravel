@props([
    'title' => '',
    'message' => '',
    'type' => 'success',
    'show' => false,
])

<div class="toast" @unless ($show) style="display: none" @endunless>
    <div class="toast__title">
        <div class="toast__title-text space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-lg icon-{{ $type }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ $title }}</span>
        </div>
        <button type="button" class="toast__title-close">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="toast__message">
        {{ $message }}
    </div>
</div>
