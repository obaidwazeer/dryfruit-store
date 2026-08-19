@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')

    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>

                <h4 class="mb-1">
                    Edit Product
                </h4>

                <p class="text-muted mb-0">
                    Update product information and catalog settings.
                </p>

            </div>


            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">

                <i class="bx bx-arrow-back me-1"></i>

                Back to Products

            </a>

        </div>


        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <div class="fw-semibold mb-2">
                    Please correct the following errors:
                </div>

                <ul class="mb-0">

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


            <div class="row g-4">


                {{-- Main Information --}}
                <div class="col-lg-8">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="mb-0">
                                Product Information
                            </h5>

                        </div>


                        <div class="card-body">


                            {{-- Product Name --}}
                            <div class="mb-4">

                                <label for="name" class="form-label fw-semibold">

                                    Product Name

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product->name) }}" required>


                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Slug --}}
                            <div class="mb-4">

                                <label for="slug" class="form-label fw-semibold">
                                    Slug
                                </label>


                                <input type="text" id="slug" name="slug"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    value="{{ old('slug', $product->slug) }}">


                                <div class="form-text">
                                    Use lowercase letters, numbers and hyphens.
                                </div>


                                @error('slug')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Short Description --}}
                            <div class="mb-4">

                                <label for="short_description" class="form-label fw-semibold">
                                    Short Description
                                </label>


                                <textarea id="short_description" name="short_description" rows="3" maxlength="500"
                                    class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $product->short_description) }}</textarea>


                                <div class="form-text">
                                    Maximum 500 characters.
                                </div>


                                @error('short_description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Description --}}
                            <div class="mb-2">

                                <label for="description" class="form-label fw-semibold">
                                    Product Description
                                </label>


                                <textarea id="description" name="description" rows="8" maxlength="10000"
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>


                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Right Column --}}
                <div class="col-lg-4">


                    {{-- Categories --}}
                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">
                                Categories
                            </h5>

                        </div>


                        <div class="card-body">


                            <label for="categories" class="form-label fw-semibold">

                                Product Categories

                                <span class="text-danger">*</span>

                            </label>


                            @if ($categories->isEmpty())

                                <div class="alert alert-warning mb-0">

                                    No active categories are available.

                                    <a href="{{ route('admin.categories.create') }}" class="fw-semibold">
                                        Create a category
                                    </a>

                                    first.

                                </div>
                            @else
                                <select id="categories" name="categories[]"
                                    class="form-select @error('categories') is-invalid @enderror" multiple size="6"
                                    required>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(in_array($category->id, old('categories', $selectedCategories)))>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach

                                </select>


                                <div class="form-text">
                                    Hold Ctrl while clicking to select multiple categories.
                                </div>


                                @error('categories')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror


                                @error('categories.*')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            @endif

                        </div>

                    </div>


                    {{-- Product Settings --}}
                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">
                                Product Settings
                            </h5>

                        </div>


                        <div class="card-body">


                            {{-- Status --}}
                            <div class="mb-4">

                                <label for="status" class="form-label fw-semibold">

                                    Status

                                    <span class="text-danger">*</span>

                                </label>


                                <select id="status" name="status"
                                    class="form-select @error('status') is-invalid @enderror" required>

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


                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Featured --}}
                            <div class="mb-4">

                                <div class="form-check form-switch">

                                    <input type="checkbox" class="form-check-input" role="switch" id="is_featured"
                                        name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>


                                    <label for="is_featured" class="form-check-label fw-semibold">
                                        Featured Product
                                    </label>

                                </div>


                                <div class="form-text">
                                    Featured products can appear in promotional sections.
                                </div>

                            </div>


                            {{-- Sort Order --}}
                            <div>

                                <label for="sort_order" class="form-label fw-semibold">
                                    Sort Order
                                </label>


                                <input type="number" id="sort_order" name="sort_order" min="0"
                                    class="form-control @error('sort_order') is-invalid @enderror"
                                    value="{{ old('sort_order', $product->sort_order) }}">


                                <div class="form-text">
                                    Lower numbers appear first.
                                </div>


                                @error('sort_order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="card">

                        <div class="card-body">

                            <div class="d-grid gap-2">

                                <button type="submit" class="btn btn-primary">

                                    <i class="bx bx-save me-1"></i>

                                    Update Product

                                </button>

                                <a href="{{ route('admin.products.variants.index', $product) }}"
                                    class="btn btn-outline-primary">

                                    <i class="bx bx-package me-1"></i>

                                    Manage Variants

                                </a>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                                    Cancel
                                </a>

                            </div>

                        </div>

                    </div>

                    {{-- Payment Methods --}}
                    <div class="card mb-4">

                        <div class="card-header">
                            <h5 class="mb-0">
                                Payment Methods
                            </h5>
                        </div>

                        <div class="card-body">

                            <p class="text-muted small mb-3">
                                Select which payment methods customers can use when
                                purchasing this product.
                            </p>


                            {{-- Cash on Delivery --}}
                            <div class="form-check mb-3">

                                <input type="hidden" name="allow_cod" value="0">

                                <input class="form-check-input" type="checkbox" name="allow_cod" id="allow_cod"
                                    value="1" @checked(old('allow_cod', $product->allow_cod))>

                                <label class="form-check-label" for="allow_cod">
                                    <strong>Cash on Delivery</strong>

                                    <div class="small text-muted">
                                        Allow customers to pay when the order is delivered.
                                    </div>
                                </label>

                            </div>


                            {{-- Online Payment --}}
                            <div class="form-check">

                                <input type="hidden" name="allow_online_payment" value="0">

                                <input class="form-check-input" type="checkbox" name="allow_online_payment"
                                    id="allow_online_payment" value="1" @checked(old('allow_online_payment', $product->allow_online_payment))>

                                <label class="form-check-label" for="allow_online_payment">
                                    <strong>Online Payment</strong>

                                    <div class="small text-muted">
                                        Allow customers to pay online for this product.
                                    </div>
                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>
    </form>


    {{-- Product Images --}}
    <div class="card mt-4">

        <div class="card-header">

            <div class="d-flex align-items-center justify-content-between">

                <div>

                    <h5 class="mb-1">
                        Product Images
                    </h5>

                    <small class="text-muted">
                        Upload high-quality images for this product.
                    </small>

                </div>


                <span class="badge bg-light text-dark">
                    {{ $product->images->count() }} / 10 images
                </span>

            </div>

        </div>


        <div class="card-body">


            {{-- Upload Form --}}
            <form method="POST" action="{{ route('admin.products.images.store', $product) }}"
                enctype="multipart/form-data">

                @csrf


                <div class="row align-items-end">


                    <div class="col-lg-8">

                        <label for="images" class="form-label fw-semibold">
                            Upload Images
                        </label>


                        <input type="file" id="images" name="images[]"
                            class="form-control @error('images') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp" multiple>


                        <div class="form-text">

                            You can upload up to
                            {{ 10 - $product->images->count() }}
                            more image(s).

                            JPG, JPEG, PNG or WebP.
                            Maximum 5 MB per image.

                        </div>


                        @error('images')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror


                        @error('images.*')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-lg-4 mt-3 mt-lg-0">

                        <button type="submit" class="btn btn-primary w-100">

                            <i class="bx bx-upload me-1"></i>

                            Upload Images

                        </button>

                    </div>

                </div>

            </form>


            @if ($product->images->isNotEmpty())

                <hr class="my-4">


                {{-- Image Gallery --}}
                <div class="row g-4">

                    @foreach ($product->images as $image)
                        <div class="col-xl-3 col-lg-4 col-md-6">

                            <div class="card h-100 border">


                                {{-- Image --}}
                                <div class="position-relative bg-light" style="height: 220px;">

                                    <img src="{{ asset('storage/' . $image->path) }}"
                                        alt="{{ $image->alt_text ?: $product->name }}" class="w-100 h-100"
                                        style="object-fit: cover;" loading="lazy">


                                    @if ($image->is_primary)
                                        <span class="badge bg-success position-absolute top-0 start-0 m-2">

                                            <i class="bx bx-star me-1"></i>

                                            Primary

                                        </span>
                                    @endif

                                </div>


                                {{-- Image Information --}}
                                <div class="card-body">

                                    <div class="small text-muted mb-3">

                                        Image #{{ $loop->iteration }}

                                    </div>


                                    {{-- Primary --}}
                                    @if (!$image->is_primary)
                                        <form method="POST"
                                            action="{{ route('admin.products.images.primary', [
                                                'product' => $product,
                                                'image' => $image,
                                            ]) }}"
                                            class="mb-2">

                                            @csrf

                                            @method('PATCH')


                                            <button type="submit" class="btn btn-outline-success btn-sm w-100">

                                                <i class="bx bx-star me-1"></i>

                                                Make Primary

                                            </button>

                                        </form>
                                    @else
                                        <button type="button" class="btn btn-success btn-sm w-100 mb-2" disabled>

                                            <i class="bx bx-check me-1"></i>

                                            Primary Image

                                        </button>
                                    @endif


                                    {{-- Delete --}}
                                    <form method="POST"
                                        action="{{ route('admin.products.images.destroy', [
                                            'product' => $product,
                                            'image' => $image,
                                        ]) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this image?');">

                                        @csrf

                                        @method('DELETE')


                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">

                                            <i class="bx bx-trash me-1"></i>

                                            Delete Image

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bx bx-image" style="font-size: 60px; opacity: .35;"></i>

                    </div>


                    <h6 class="mb-2">
                        No product images yet
                    </h6>


                    <p class="text-muted mb-0">
                        Upload product images using the uploader above.
                    </p>

                </div>

            @endif

        </div>

    </div>

    </div>


@endsection
