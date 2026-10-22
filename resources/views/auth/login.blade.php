@extends('layouts.main')
@section('title', $title)

@section('content')
    <form action="{{ route('authenticate') }}" method="post" class="card form-narrow">
        @csrf
        <label class="field">
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label>
        <label class="field">
            <span>Password</span>
            <input type="password" name="password" required>
        </label>
        <div class="actions">
            <button type="submit" class="button">Login</button>
            <a href="{{ route('events.list') }}" class="button button-outline">Cancel</a>
        </div>
    </form>
@endsection
