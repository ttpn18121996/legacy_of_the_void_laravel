@extends('layouts.app')

@use('App\Enums\ViewMode')

@section('title', 'Actress management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/actresses-index.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('static/css/toast.css?v='.time()) }}">
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
            <a
                href="{{ route('admin.actresses.index', ['view_mode' => $viewMode === ViewMode::GRID->value ? ViewMode::TABLE->value : ViewMode::GRID->value]) }}"
                class="btn btn--secondary"
            >
                {!! ViewMode::display($viewMode === ViewMode::GRID->value ? ViewMode::TABLE : ViewMode::GRID) !!}
            </a>
            <a href="{{ route('admin.actresses.create') }}" class="btn btn--primary">Add new</a>
        </div>

        @if($viewMode === ViewMode::GRID->value)
            <div class="data--grid">
                @forelse($actresses as $actress)
                    <x-actress-item :actress="$actress" />
                @empty
                @endforelse
            </div>
        @else
            <table class="table hide-on-mobile mb-4" id="actresses-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thumbnail</th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actresses as $actress)
                        <tr id="actress{{ $actress->id }}">
                            <td>{{ $actress->id }}</td>
                            <td>
                                <div class="table__thumbnail">
                                    <img src="{{ $actress->public_path }}" alt="{{ $actress->name }}">
                                </div>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <div class="table__actions space-x-2">
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">No data found.</td></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif

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
