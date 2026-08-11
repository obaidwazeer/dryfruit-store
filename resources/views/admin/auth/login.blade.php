<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div>
        <h1>Admin Login</h1>

        @if ($errors->any())
            <div>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <div>
                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >
            </div>

            <div>
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >
            </div>

            <div>
                <label>
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                    >

                    Remember me
                </label>
            </div>

            <button type="submit">
                Login
            </button>
        </form>
    </div>
</body>
</html>
