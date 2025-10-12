@props([
    'title' => '',
    'body',
    'footer',
])

<div class="modal-wrapper">
    <div class="modal__overlay"></div>
    <div class="modal">
        <div class="modal__header">
            <div class="modal__title">{{ $title }}</div>
            <div class="modal__close">
                <button type="button" class="btn--secondary btn--circle close-modal">&times;</button>
            </div>
        </div>
        <div class="modal__body">
            {{ $body }}
        </div>
        @if (isset($footer))
            <div class="modal__footer">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
