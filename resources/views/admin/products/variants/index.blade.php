<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Variants - {{ $product->name }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <h1>
        Variants: {{ $product->name }}
    </h1>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p>
        <a href="{{ route('admin.products.variants.create', $product) }}">
            Add Variant
        </a>
    </p>

    <p>
        <a href="{{ route('admin.products.index') }}">
            Back to Products
        </a>
    </p>

    <hr>

    @if ($variants->count())

        <table border="1" cellpadding="10">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Weight</th>
                    <th>Price</th>
                    <th>Compare Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($variants as $variant)
                    <tr>

                        <td>
                            {{ $variant->name }}
                        </td>

                        <td>
                            {{ $variant->sku }}
                        </td>

                        <td>
                            {{ number_format($variant->weight_grams) }}g
                        </td>

                        <td>
                            Rs. {{ number_format($variant->price, 2) }}
                        </td>

                        <td>
                            @if ($variant->compare_at_price)
                                Rs.
                                {{ number_format($variant->compare_at_price, 2) }}
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            {{ $variant->stock_quantity }}
                        </td>

                        <td>
                            {{ $variant->is_active ? 'Active' : 'Inactive' }}
                        </td>

                        <td>

                            <a
                                href="{{ route('admin.products.variants.edit', [
                                    'product' => $product,
                                    'variant' => $variant,
                                ]) }}">
                                Edit
                            </a>

                            <form method="POST"
                                action="{{ route('admin.products.variants.destroy', [
                                    'product' => $product,
                                    'variant' => $variant,
                                ]) }}"
                                style="display:inline;">

                                @csrf

                                @method('DELETE')

                                <button type="submit">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

        <br>

        {{ $variants->links() }}
    @else
        <p>
            No variants found for this product.
        </p>

    @endif

</body>

</html>
