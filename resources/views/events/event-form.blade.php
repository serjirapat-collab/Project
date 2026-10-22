{{-- Used for both create ($event is null) and update. --}}
@extends('layouts.main')
@section('title', $title)

@section('content')
    <form action="{{ $event ? route('events.update', ['event' => $event->code]) : route('events.create') }}" method="post"
        class="card">
        @csrf
        <label class="field">
            <span>Code *</span>
            <input type="text" name="code" value="{{ old('code', $event?->code) }}" required>
        </label>
        <label class="field">
            <span>Event name *</span>
            <input type="text" name="name" value="{{ old('name', $event?->name) }}" required>
        </label>
        <label class="field">
            <span>Date &amp; time *</span>
            <input type="datetime-local" name="start_at" value="{{ old('start_at', $event?->start_at?->format('Y-m-d\TH:i')) }}"
                required>
        </label>
        <label class="field">
            <span>Location *</span>
            <select name="location" required>
                <option value="" disabled @selected(old('location', $event?->location?->code) === null)>-- Select location --</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->code }}" @selected($location->code === old('location', $event?->location?->code))>
                        [{{ $location->code }}] {{ $location->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="field">
            <span>Category *</span>
            <select name="category" required>
                <option value="" disabled @selected(old('category', $event?->category?->code) === null)>-- Select category --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->code }}" @selected($category->code === old('category', $event?->category?->code))>
                        [{{ $category->code }}] {{ $category->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="field">
            <span>For faculty *</span>
            {{-- An SMO only gets their own faculty here; the admin gets all three. --}}
            <select name="faculty" required>
                @foreach ($faculties as $code => $name)
                    <option value="{{ $code }}" @selected($code === old('faculty', $event?->faculty))>{{ $name }}</option>
                @endforeach
            </select>
        </label>
        <label class="field">
            <span>Description *</span>
            <textarea name="description" rows="6" required>{{ old('description', $event?->description) }}</textarea>
        </label>

        <div class="actions">
            <button type="submit" class="button">{{ $event ? 'Update' : 'Create' }}</button>
            <a href="{{ $event ? route('events.view', ['event' => $event->code]) : route('events.list') }}"
                class="button button-outline">Cancel</a>
        </div>
    </form>
@endsection
