@extends('layouts.app')

@section('title', 'Edit category')

@section('content')
    <div class="main__header">
        @error('category')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror
    </div>
    <div class="main__body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="form">
            @csrf
            @method('PUT')

            <div class="form-input">
                <label for="title" class="required">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title', $category->title) }}" placeholder="Title" autocomplete="off" autofocus />
                @error('title')
                    <label for="title" class="text-error">{{ $message }}</label>
                @enderror
            </div>

            <div class="form-input">
                <label for="tags">Tags</label>
                <x-selection-list name="tags" :items="$tags" :selected-items="$selectedTags" size="sm" />
            </div>

            <div class="form-button space-x-2">
                <button type="submit" class="btn btn--primary">Save</button>
                <a class="btn btn--secondary" href="{{  route('admin.categories.index') }}">Back to list</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        lotv.useSelectionList();
    </script>
@endpush
