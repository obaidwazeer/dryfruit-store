<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <h1>Products</h1>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    @can('products.create')
        <a href="{{ route('admin.products.create') }}">
            Create Product
        </a>
    @endcan

    <hr>

    <form method="GET" action="{{ route('admin.products.index') }}">

        <input type="search" name="search" placeholder="Search products..." value="{{ request('search') }}">

        <button type="submit">
            Search
        </button>

    </form>

    <br>

    @if ($products->count())

        <table border="1" cellpadding="10">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Categories</th>
                    <th>Status</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($products as $product)
                    <tr>

                        <td>
                            {{ $product->name }}
                        </td>

                        <td>
                            @forelse ($product->categories as $category)
                                {{ $category->name }}@if (!$loop->last)
                                    ,
                                @endif
                                @empty
                                    No category
                                @endforelse
                            </td>

                            <td>
                                {{ ucfirst($product->status) }}
                            </td>

                            <td>
                                {{ $product->is_featured ? 'Yes' : 'No' }}
                            </td>

                            <td>

                                @can('products.update')
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}">
                                        Edit
                                    </a>
                                @endcan

                                @can('products.delete')
                                    <form method="POST"
                                        action="{{ route('admin.products.destroy', $product) }}"
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

            <br>

            {{ $products->links() }}
        @else
            <p>No products found.</p>

        @endif

    </body>

    </html>
