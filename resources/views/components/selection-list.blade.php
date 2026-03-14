@props([
    'name',
    'items' => [],
    'selectedItems' => [],
    'size' => 'md',
])

<div class="selection-list">
    <div class="selection-list__search">
        <input type="search" class="selection-list__search-input" placeholder="Search..." />
    </div>
    <div class="selection-list__items selection-list__items--{{ $size }} space-y-2" id="{{ $name }}-list">
        @forelse($items as $item)
            <div class="selection-list__checkbox">
                <input
                    id="{{ $name }}-{{ $item['value'] }}"
                    type="checkbox"
                    name="{{ $name }}[]"
                    value="{{ $item['value'] }}"
                    {{ in_array($item['value'], $selectedItems) ? 'checked' : '' }}
                />
                <label for="{{ $name }}-{{ $item['value'] }}">{{ $item['label'] }}</label>
            </div>
        @empty
        @endforelse
    </div>
</div>
