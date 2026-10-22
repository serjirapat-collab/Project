{{-- Used for both create ($location is null) and update. --}}
@extends('layouts.main')
@section('title', $title)

@section('content')
    <form action="{{ $location ? route('locations.update', ['location' => $location->code]) : route('locations.create') }}"
        method="post" class="card">
        @csrf
        <label class="field">
            <span>Code *</span>
            <input type="text" name="code" value="{{ old('code', $location?->code) }}" required>
        </label>
        <label class="field">
            <span>Location name *</span>
            <input type="text" name="name" value="{{ old('name', $location?->name) }}" required>
        </label>
        <label class="field">
            <span>Description *</span>
            <textarea name="description" rows="5" required>{{ old('description', $location?->description) }}</textarea>
        </label>

        <div class="actions">
            <button type="submit" class="button">{{ $location ? 'Update' : 'Create' }}</button>
            <a href="{{ $location ? route('locations.view', ['location' => $location->code]) : route('locations.list') }}"
                class="button button-outline">Cancel</a>
        </div>
    </form>
@endsection
