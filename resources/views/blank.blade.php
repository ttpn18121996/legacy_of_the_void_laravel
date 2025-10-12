@extends('layouts.app')

@section('title', 'Blank')

@section('content')
    <div class="main__header">
        <x-search-form action="{{ route('videos.index') }}" />
    </div>
    <div class="main__body">
        This is a blank page.
    </div>
@endsection
