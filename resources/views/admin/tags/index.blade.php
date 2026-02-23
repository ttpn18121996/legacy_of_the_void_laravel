@extends('layouts.app')

@section('title', 'Tag management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('static/css/modal.css?v='.time()) }}">
    <link rel="stylesheet" href="{{ asset('static/css/toast.css?v='.time()) }}">
@endpush

@section('content')
    <div class="main__body">
        <div class="main__body-form">
            <form action="" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-input" name="title" id="title" placeholder="Tag title">
                    @error('title')
                        <label for="title" class="text-error">{{ $message }}</label>
                    @enderror
                </div>
                <div>
                    <button class="btn btn--primary">Save</button>
                </div>
            </form>
        </div>

        <table class="table" id="tags-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $tag)
                    <tr id="tag{{ $tag->id }}">
                        <td>{{ $tag->id }}</td>
                        <td class="editable">
                            <input type="text" value="{{ $tag->title }}">
                        </td>
                        <td id="slug{{ $tag->id }}">{{ $tag->slug }}</td>
                        <td>
                            <div class="table__actions space-x-2">
                                <button
                                    data-id="{{ $tag->id }}"
                                    data-url="{{ route('admin.tags.update', ['id' => $tag->id]) }}"
                                    class="btn--sm btn--primary btn-edit"
                                >
                                    Edit
                                </button>
                                <button
                                    data-id="{{ $tag->id }}"
                                    data-url="{{ route('admin.tags.destroy', ['id' => $tag->id]) }}"
                                    class="btn--sm btn--secondary btn-delete"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No data found.</td></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('static/js/confirm-dialog.js?v='.time()) }}"></script>
    <script src="{{ asset('static/js/admin/tags-index.js?v='.time()) }}"></script>
    <script>
        tagsIndex();
    </script>
@endpush
