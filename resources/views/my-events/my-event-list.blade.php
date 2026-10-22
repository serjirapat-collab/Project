@extends('layouts.main')
@section('title', $title)

@section('content')
    @if (Auth::user()->isSmo())
        <div class="actions">
            <a href="{{ route('events.create-form') }}" class="button button-accent">+ Create Event</a>
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Event</th>
                <th>Date &amp; Time</th>
                <th>Location</th>
                <th>Category</th>
                @unless (Auth::user()->isStudent())
                    <th>Faculty</th>
                    <th class="number">Registered</th>
                @endunless
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
                    @unless (Auth::user()->isStudent())
                        <td><span class="badge badge-{{ strtolower($event->faculty) }}">{{ $event->facultyName() }}</span></td>
                        <td class="number">{{ $event->students_count }}</td>
                    @endunless
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">
                        @if (Auth::user()->isStudent())
                            You have not registered for any events yet. <a href="{{ route('events.list') }}" class="link">Browse events</a>
                        @else
                            No events yet.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $events->links() }}
@endsection
