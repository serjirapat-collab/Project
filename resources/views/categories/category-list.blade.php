@extends('layouts.main')
@section('title', $title)

@section('content')
    <form action="{{ route('categories.list') }}" method="get" class="search-bar">
        <input type="search" name="term" value="{{ $criteria['term'] }}" placeholder="Search code or name">
        <button type="submit" class="button">Search</button>
        @can('create', \App\Models\Category::class)
            <a href="{{ route('categories.create-form') }}" class="button button-accent">+ Create Category</a>
        @endcan
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Category</th>
                <th class="number">No. of Events</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td><a href="{{ route('categories.view', ['category' => $category->code]) }}" class="link code">{{ $category->code }}</a></td>
                    <td>{{ $category->name }}</td>
                    <td class="number">{{ $category->events_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="empty">No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $categories->withQueryString()->links() }}
@endsection
