@extends('layouts.main')
@section('title', $title)

@section('content')
    @if ($location)
        <p class="filter-note">
            Events at <a href="{{ route('locations.view', ['location' => $location->code]) }}" class="link">{{ $location->name }}</a>
            &middot; <a href="{{ route('events.list') }}" class="link">Show all events</a>
        </p>
    @endif
    @if ($category)
        <p class="filter-note">
            Events in category <a href="{{ route('categories.view', ['category' => $category->code]) }}" class="link">{{ $category->name }}</a>
            &middot; <a href="{{ route('events.list') }}" class="link">Show all events</a>
        </p>
    @endif

    <form action="{{ route('events.list') }}" method="get" class="search-bar">
        {{-- Keep the location/category filter while searching. --}}
        @if ($criteria['location'] !== '')
            <input type="hidden" name="location" value="{{ $criteria['location'] }}">
        @endif
        @if ($criteria['category'] !== '')
            <input type="hidden" name="category" value="{{ $criteria['category'] }}">
        @endif
        <input type="search" name="term" value="{{ $criteria['term'] }}" placeholder="Search code or name">
        <select name="faculty">
            <option value="">All faculties</option>
            @foreach ($faculties as $code => $name)
                <option value="{{ $code }}" @selected($criteria['faculty'] === $code)>{{ $name }}</option>
            @endforeach
        </select>
        <button type="submit" class="button">Search</button>
        @can('create', \App\Models\Event::class)
            <a href="{{ route('events.create-form') }}" class="button button-accent">+ Create Event</a>
        @endcan
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Event</th>
                <th>Date &amp; Time</th>
                <th>Location</th>
                <th>Category</th>
                <th>Faculty</th>
                <th class="number">Registered</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
                <tr>
                    <td><a href="{{ route('events.view', ['event' => $event->code]) }}" class="link code">{{ $event->code }}</a></td>
                    <td>{{ $event->name }}</td>
                    <td class="nowrap">{{ $event->start_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $event->location->name }}</td>
                    <td>{{ $event->category->name }}</td>
                    <td><span class="badge badge-{{ strtolower($event->faculty) }}">{{ $event->facultyName() }}</span></td>
                    <td class="number">{{ $event->students_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">No events found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $events->withQueryString()->links() }}
@endsection
