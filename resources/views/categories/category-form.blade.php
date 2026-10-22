{{-- Used for both create ($category is null) and update. --}}
@extends('layouts.main')
@section('title', $title)

@section('content')
    <form action="{{ $category ? route('categories.update', ['category' => $category->code]) : route('categories.create') }}"
        method="post" class="card">
        @csrf
        <label class="field">
            <span>Code *</span>
            <input type="text" name="code" value="{{ old('code', $category?->code) }}" required>
        </label>
        <label class="field">
            <span>Category name *</span>
            <input type="text" name="name" value="{{ old('name', $category?->name) }}" required>
        </label>
        <label class="field">
            <span>Description *</span>
            <textarea name="description" rows="5" required>{{ old('description', $category?->description) }}</textarea>
        </label>

        <div class="actions">
            <button type="submit" class="button">{{ $category ? 'Update' : 'Create' }}</button>
            <a href="{{ $category ? route('categories.view', ['category' => $category->code]) : route('categories.list') }}"
                class="button button-outline">Cancel</a>
        </div>
    </form>
@endsection
