@extends('layouts.main')
@section('title', $title)

@section('content')
    @if ($event)
        <p class="filter-note">
            Location of event <a href="{{ route('events.view', ['event' => $event->code]) }}" class="link">{{ $event->name }}</a>
            &middot; <a href="{{ route('locations.list') }}" class="link">Show all locations</a>
        </p>
    @endif

    <form action="{{ route('locations.list') }}" method="get" class="search-bar">
        @if ($criteria['event'] !== '')
            <input type="hidden" name="event" value="{{ $criteria['event'] }}">
        @endif
        <input type="search" name="term" value="{{ $criteria['term'] }}" placeholder="Search code or name">
        <button type="submit" class="button">Search</button>
        @can('create', \App\Models\Location::class)
            <a href="{{ route('locations.create-form') }}" class="button button-accent">+ Create Location</a>
        @endcan
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Location</th>
                <th class="number">No. of Events</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($locations as $location)
                <tr>
                    <td><a href="{{ route('locations.view', ['location' => $location->code]) }}" class="link code">{{ $location->code }}</a></td>
                    <td>{{ $location->name }}</td>
                    <td class="number">{{ $location->events_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="empty">No locations found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $locations->withQueryString()->links() }}
@endsection
