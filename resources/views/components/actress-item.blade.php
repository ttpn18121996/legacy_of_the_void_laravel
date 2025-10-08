@props([
    'actress',
])

@isset($actress)
    <div class="data__item--grid">
        <div class="data__card--grid">
            <a href="{{ route('actresses.show', $actress) }}">
                <div class="actress-card__thumbnails">
                    <img src="{{ $actress->public_path }}" alt="{{ $actress->name }}">
                </div>
                <p class="actress-card__name">
                    {{ $actress->name }}
                    @if ($actress->name != $actress->another_name)
                    <br>&#40;{{ $actress->another_name }}&#41;
                    @endif
                </p>
            </a>
        </div>
    </div>
@endisset
