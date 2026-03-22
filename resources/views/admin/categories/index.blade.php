@extends('layouts.app')

@section('title', 'Category management')

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

        <table class="table hide-on-mobile" id="categories-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Tags</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr id="category{{ $category->id }}">
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->title }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            <div class="table__tags space-x-2">
                                @forelse($category->tags as $tag)
                                    <a href="{{ get_filter_tag_url($tag->slug) }}">{{ $tag->title_for_human }}</a>
                                @empty
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <div class="table__actions space-x-2">
                                <a href="{{ route('admin.categories.edit', ['id' => $category->id]) }}" class="btn--sm btn--primary">Edit</a>
                                <button
                                    data-id="{{ $category->id }}"
                                    data-url="{{  route('admin.categories.destroy', ['id' => $category->id]) }}"
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
    <script src="{{ asset('static/js/admin/categories-index.js?v='.time()) }}"></script>
    <script>
        categoriesIndex();
    </script>
@endpush
