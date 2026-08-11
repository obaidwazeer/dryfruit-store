<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Product - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <h1>Edit Product</h1>

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

    <form method="POST" action="{{ route('admin.products.update', $product) }}">

        @csrf

        @method('PUT')

        <div>

            <label for="name">
                Product Name
            </label>

            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>

        </div>

        <br>

        <div>

            <label for="slug">
                Slug
            </label>

            <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}">

        </div>

        <br>

        <div>

            <label for="short_description">
                Short Description
            </label>

            <textarea id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>

        </div>

        <br>

        <div>

            <label for="description">
                Description
            </label>

            <textarea id="description" name="description" rows="8">{{ old('description', $product->description) }}</textarea>

        </div>

        <br>

        <div>

            <label for="categories">
                Categories
            </label>

            @php
                $selectedCategories = old('categories', $product->categories->pluck('id')->toArray());
            @endphp

            <select id="categories" name="categories[]" multiple required>

                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(in_array($category->id, $selectedCategories))>
                        {{ $category->name }}
                    </option>
                @endforeach

            </select>

        </div>

        <br>

        <div>

            <label for="status">
                Status
            </label>

            <select id="status" name="status" required>

                <option value="draft" @selected(old('status', $product->status) === 'draft')>
                    Draft
                </option>

                <option value="active" @selected(old('status', $product->status) === 'active')>
                    Active
                </option>

                <option value="archived" @selected(old('status', $product->status) === 'archived')>
                    Archived
                </option>

            </select>

        </div>

        <br>

        <div>

            <label for="sort_order">
                Sort Order
            </label>

            <input type="number" id="sort_order" name="sort_order"
                value="{{ old('sort_order', $product->sort_order) }}"
                min="0">

        </div>

        <br>

        <div>

            <label>

                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>

                Featured Product

            </label>

        </div>

        <br>

        <button type="submit">
            Update Product
        </button>

    </form>

    <br>

    <a href="{{ route('admin.products.index') }}">
        Back to Products
    </a>

</body>

</html>
