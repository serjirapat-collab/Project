<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - CMU Event Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ route('events.list') }}" class="brand">CMU Event Hub</a>

            <nav>
                <ul class="menu">
                    <li><a href="{{ route('events.list') }}" @class(['active' => Route::is('events.*')])>Event</a></li>
                    <li><a href="{{ route('locations.list') }}" @class(['active' => Route::is('locations.*')])>Location</a></li>
                    <li><a href="{{ route('categories.list') }}" @class(['active' => Route::is('categories.*')])>Category</a></li>
                    @auth
                        <li><a href="{{ route('my-events.list') }}" @class(['active' => Route::is('my-events.*')])>List Event</a></li>
                    @endauth
                    @can('list', \App\Models\User::class)
                        <li><a href="{{ route('users.list') }}" @class(['active' => Route::is('users.*')])>User</a></li>
                    @endcan
                </ul>
            </nav>

            <div class="user-panel">
                @auth
                    <span class="user-name">
                        {{ Auth::user()->name }}
                        <span class="badge">{{ Auth::user()->role }}{{ Auth::user()->faculty() ? ' · ' . Auth::user()->faculty() : '' }}</span>
                    </span>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="button button-outline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="button">Login</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container">
        <h1>@yield('title')</h1>

        <div class="notifications">
            @session('status')
                <div class="notice notice-success" role="status">{{ $value }}</div>
            @endsession
            @foreach ($errors->all() as $message)
                <div class="notice notice-error" role="alert">{{ $message }}</div>
            @endforeach
        </div>

        @yield('content')
    </main>

    <footer class="site-footer">
        CMU Event Hub &middot; Term Project 1/2569
    </footer>
</body>

</html>
