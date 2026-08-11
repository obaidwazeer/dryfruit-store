<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Category - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <h1>Create Category</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.store') }}">

        @csrf

        <div>
            <label for="name">
                Name
            </label>

            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <br>

        <div>
            <label for="slug">
                Slug
            </label>

            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="almonds">

            <small>
                Leave blank to generate later.
            </small>
        </div>

        <br>

        <div>
            <label for="description">
                Description
            </label>

            <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
        </div>

        <br>

        <div>
            <label for="sort_order">
                Sort Order
            </label>

            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
        </div>

        <br>

        <div>
            <label>
                <input type="checkbox" name="is_active" value="1" checked>

                Active
            </label>
        </div>

        <br>

        <button type="submit">
            Create Category
        </button>

    </form>

    <br>

    <a href="{{ route('admin.categories.index') }}">
        Back to Categories
    </a>

</body>

</html>
