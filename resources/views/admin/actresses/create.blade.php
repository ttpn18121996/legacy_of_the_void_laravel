@extends('layouts.app')

@section('title', 'Create actress')

@section('content')
    <div class="main__header">
        @error('actress')
            <div class="alert alert-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="main__body">
        <form action="{{ route('admin.actresses.store') }}" method="POST" enctype="multipart/form-data" class="form">
            @csrf
            <div class="form-input">
                <label for="name" class="required">Name</label>
                <input id="name" type="text" name="name" placeholder="Name" autocomplete="off" autofocus />
                @error('name')
                    <label for="name" class="text-error">{{ $message }}</label>
                @enderror
            </div>

            <div class="form-input">
                <label for="another_name">Another name</label>
                <input id="another_name" type="text" name="another_name" placeholder="Another name" autocomplete="off" autofocus />
                @error('another_name')
                    <label for="another_name" class="text-error">{{ $message }}</label>
                @enderror
            </div>
            
            <div class="form-input">
                <label for="tags">Tags</label>
                <x-selection-list name="tags" :items="$tags" :selected-items="[]" />
            </div>

            <div class="form-button space-x-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-secondary" href="{{  route('admin.actresses.index') }}">Back to list</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        lotv.useSelectionList();
    </script>
@endpush
