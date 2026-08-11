<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Edit Variant - {{ $product->name }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <h1>
        Edit Variant
    </h1>

    <p>
        Product: {{ $product->name }}
    </p>

    @if ($errors->any())

        <div>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        method="POST"
        action="{{ route(
            'admin.products.variants.update',
            [
                'product' => $product,
                'variant' => $variant,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')

        <div>

            <label for="name">
                Variant Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old(
                    'name',
                    $variant->name
                ) }}"
                required
            >

        </div>

        <br>

        <div>

            <label for="sku">
                SKU
            </label>

            <input
                type="text"
                id="sku"
                name="sku"
                value="{{ old(
                    'sku',
                    $variant->sku
                ) }}"
                required
            >

        </div>

        <br>

        <div>

            <label for="weight_grams">
                Weight (grams)
            </label>

            <input
                type="number"
                id="weight_grams"
                name="weight_grams"
                value="{{ old(
                    'weight_grams',
                    $variant->weight_grams
                ) }}"
                min="1"
                required
            >

        </div>

        <br>

        <div>

            <label for="price">
                Price
            </label>

            <input
                type="number"
                id="price"
                name="price"
                value="{{ old(
                    'price',
                    $variant->price
                ) }}"
                min="0"
                step="0.01"
                required
            >

        </div>

        <br>

        <div>

            <label for="compare_at_price">
                Compare At Price
            </label>

            <input
                type="number"
                id="compare_at_price"
                name="compare_at_price"
                value="{{ old(
                    'compare_at_price',
                    $variant->compare_at_price
                ) }}"
                min="0"
                step="0.01"
            >

        </div>

        <br>

        <div>

            <label for="stock_quantity">
                Stock Quantity
            </label>

            <input
                type="number"
                id="stock_quantity"
                name="stock_quantity"
                value="{{ old(
                    'stock_quantity',
                    $variant->stock_quantity
                ) }}"
                min="0"
                required
            >

        </div>

        <br>

        <div>

            <label for="low_stock_threshold">
                Low Stock Threshold
            </label>

            <input
                type="number"
                id="low_stock_threshold"
                name="low_stock_threshold"
                value="{{ old(
                    'low_stock_threshold',
                    $variant->low_stock_threshold
                ) }}"
                min="0"
                required
            >

        </div>

        <br>

        <div>

            <label for="sort_order">
                Sort Order
            </label>

            <input
                type="number"
                id="sort_order"
                name="sort_order"
                value="{{ old(
                    'sort_order',
                    $variant->sort_order
                ) }}"
                min="0"
            >

        </div>

        <br>

        <div>

            <label>

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(
                        old(
                            'is_active',
                            $variant->is_active
                        )
                    )
                >

                Active

            </label>

        </div>

        <br>

        <button type="submit">
            Update Variant
        </button>

    </form>

    <br>

    <a href="{{ route(
        'admin.products.variants.index',
        $product
    ) }}">
        Back to Variants
    </a>

</body>
</html>
