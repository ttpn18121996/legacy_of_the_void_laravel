@extends('layouts.app')

@use('App\Enums\ActressSort')

@section('title', 'Actress management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/toast.css?v='.time()) }}">
    <link rel="stylesheet" href="{{ asset('static/css/actresses-index.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/admin.css') }}">
@endpush

@section('content')
    @php
        $q = request()->query('q');
    @endphp

    <div class="main__header">
        <x-search-form />
    </div>
    <div class="main__body">
        <div class="main__body-actions space-x-2">
            <a href="{{ route('admin.actresses.create') }}" class="btn btn--primary">Add new</a>
        </div>

        <div class="data__controls">
            <form action="" method="GET" class="space-x-4">
                <input type="hidden" name="sort_mode" value="without" />
                <div class="data__control-item space-x-2">
                    <div>Without:</div>
                    <select name="sort_by">
                        @foreach(ActressSort::labels() as $value => $label)
                            <option value="{{ $value }}" {{ request()->query('sort_by', ActressSort::NOTHING->value) === $value ? 'selected' : '' }}>{{  $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="data__control-item">
                    <button class="btn--sm btn--primary">Apply</button>
                </div>
            </form>
        </div>

        <div class="flexible-table">
            @forelse($actresses as $actress)
                <div class="flexible-table__row">
                    <div class="flexible-table__cell-header">
                        <a href="{{ route('actresses.show', ['id' => $actress->id]) }}" target="_blank">
                            <div class="table__thumbnail">
                                <img src="{{ $actress->public_path }}" alt="{{ $actress->name }}">
                            </div>
                        </a>
                    </div>
                    <div class="flexible-table__cell-body">
                        <div class="flexible-table__cell-item">
                            <p class="mb-4">
                                <a href="{{ route('actresses.show', ['id' => $actress->id]) }}" target="_blank">
                                    {!! $actress->highlightTitle($q, 'name') !!}
                                    @if ($actress->name != $actress->another_name)
                                    <br>&#40;{!! $actress->highlightTitle($q, 'another_name') !!}&#41;
                                    @endif
                                </a>
                            </p>
                            <div class="table__tags space-x-2">
                                @foreach($actress->tags as $tag)
                                    <a href="{{ get_filter_tag_url($tag->slug) }}">{{ $tag->title_for_human }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flexible-table__cell-footer">
                        <a href="{{ route('admin.actresses.edit', ['id' => $actress->id]) }}" class="btn--sm btn--primary">Edit</a>
                        <button
                            data-id="{{ $actress->id }}"
                            data-url="{{ route('admin.actresses.update-thumbnail', ['id' => $actress->id]) }}"
                            class="btn--sm btn--info btn-update-thumbnail"
                        >
                            Update thumbnail
                        </button>
                        <button
                            data-id="{{ $actress->id }}"
                            data-url="{{ route('admin.actresses.destroy', ['id' => $actress->id]) }}"
                            class="btn--sm btn--danger btn-delete"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            @empty
            @endforelse
        </div>

        {{ $actresses->links('vendor.pagination') }}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/confirm-dialog.js?v='.time()) }}"></script>
    <script src="{{ asset('static/js/admin/actresses-index.js?v='.time()) }}"></script>
    <script>
        actressesIndex();
    </script>
@endpush
