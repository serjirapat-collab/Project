@extends('layouts.main')
@section('title', $title)

@section('content')
    <div class="actions">
        <a href="{{ route('categories.list') }}" class="button button-outline">&lt; All Categories</a>
        <a href="{{ route('events.list', ['category' => $category->code]) }}" class="button button-outline">View Event</a>
        @can('update', $category)
            <a href="{{ route('categories.update-form', ['category' => $category->code]) }}" class="button">Update</a>
        @endcan
        @can('delete', $category)
            <form action="{{ route('categories.delete', ['category' => $category->code]) }}" method="post"
                onsubmit="return confirm('Delete {{ $category->name }}?')">
                @csrf
                <button type="submit" class="button button-danger">Delete</button>
            </form>
        @endcan
    </div>

    <div class="card">
        <dl class="detail">
            <dt>Code</dt>
            <dd class="code">{{ $category->code }}</dd>

            <dt>Category</dt>
            <dd>{{ $category->name }}</dd>

            <dt>Description</dt>
            <dd>{{ $category->description }}</dd>

            <dt>No. of Events</dt>
            <dd>{{ $category->events_count }}</dd>
        </dl>
    </div>
@endsection
