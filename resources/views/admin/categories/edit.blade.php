<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Category - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <h1>Edit Category</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update', $category) }}">

        @csrf

        @method('PUT')

        <div>
            <label for="name">
                Name
            </label>

            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
        </div>

        <br>

        <div>
            <label for="slug">
                Slug
            </label>

            <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
        </div>

        <br>

        <div>
            <label for="description">
                Description
            </label>

            <textarea id="description" name="description" rows="5">{{ old('description', $category->description) }}</textarea>
        </div>

        <br>

        <div>
            <label for="sort_order">
                Sort Order
            </label>

            <input type="number" id="sort_order" name="sort_order"
                value="{{ old('sort_order', $category->sort_order) }}" min="0">
        </div>

        <br>

        <div>
            <label>
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))>

                Active
            </label>
        </div>

        <br>

        <button type="submit">
            Update Category
        </button>

    </form>

    <br>

    <a href="{{ route('admin.categories.index') }}">
        Back to Categories
    </a>

</body>

</html>
