<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categories - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <h1>Categories</h1>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    @can('categories.create')
        <a href="{{ route('admin.categories.create') }}">
            Create Category
        </a>
    @endcan

    <hr>

    @if ($categories->count())

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Sort Order</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($categories as $category)
                    <tr>
                        <td>
                            {{ $category->name }}
                        </td>

                        <td>
                            {{ $category->slug }}
                        </td>

                        <td>
                            @if ($category->is_active)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>

                        <td>
                            {{ $category->sort_order }}
                        </td>

                        <td>

                            @can('categories.update')
                                <a
                                    href="{{ route('admin.categories.edit', $category) }}">
                                    Edit
                                </a>
                            @endcan

                            @can('categories.delete')
                                <form method="POST"
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    style="display:inline;">
                                    @csrf

                                    @method('DELETE')

                                    <button type="submit">
                                        Delete
                                    </button>
                                </form>
                            @endcan

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <div>
            {{ $categories->links() }}
        </div>
    @else
        <p>No categories found.</p>

    @endif

</body>

</html>
