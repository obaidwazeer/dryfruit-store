@extends('admin.layouts.app')

@section('title', 'Edit Variant - ' . $product->name)

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

            <div>

                <h4 class="mb-1">
                    Edit Product Variant
                </h4>

                <p class="text-muted mb-0">
                    Update the variant information for
                    <strong>{{ $product->name }}</strong>.
                </p>

            </div>

            <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-outline-secondary">

                <i class="bx bx-arrow-back me-1"></i>

                Back to Variants

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


        <form method="POST" action="{{ route('admin.products.variants.update', [$product, $variant]) }}">

            @csrf

            @method('PUT')


            <div class="row">

                {{-- Main Information --}}
                <div class="col-lg-8">

                    <div class="card mb-4">

                        <div class="card-header bg-transparent">

                            <h5 class="mb-0">
                                Variant Information
                            </h5>

                        </div>


                        <div class="card-body">

                            {{-- Name --}}
                            <div class="mb-4">

                                <label for="name" class="form-label">

                                    Variant Name
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $variant->name) }}"
                                    class="form-control @error('name') is-invalid @enderror" maxlength="100" required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="row">

                                {{-- SKU --}}
                                <div class="col-md-6 mb-4">

                                    <label for="sku" class="form-label">

                                        SKU
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="text" id="sku" name="sku"
                                        value="{{ old('sku', $variant->sku) }}"
                                        class="form-control @error('sku') is-invalid @enderror" maxlength="100" required>

                                    @error('sku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- Weight --}}
                                <div class="col-md-6 mb-4">

                                    <label for="weight_grams" class="form-label">

                                        Weight
                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <input type="number" id="weight_grams" name="weight_grams"
                                            value="{{ old('weight_grams', $variant->weight_grams) }}"
                                            class="form-control @error('weight_grams') is-invalid @enderror" min="1"
                                            max="100000" required>

                                        <span class="input-group-text">
                                            grams
                                        </span>

                                    </div>

                                    @error('weight_grams')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>


                            <div class="row">

                                {{-- Price --}}
                                <div class="col-md-6 mb-4">

                                    <label for="price" class="form-label">

                                        Selling Price
                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Rs.
                                        </span>

                                        <input type="number" id="price" name="price"
                                            value="{{ old('price', $variant->price) }}"
                                            class="form-control @error('price') is-invalid @enderror" step="0.01"
                                            min="0" required>

                                    </div>

                                    @error('price')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- Compare Price --}}
                                <div class="col-md-6 mb-4">

                                    <label for="compare_at_price" class="form-label">

                                        Compare-at Price

                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Rs.
                                        </span>

                                        <input type="number" id="compare_at_price" name="compare_at_price"
                                            value="{{ old('compare_at_price', $variant->compare_at_price) }}"
                                            class="form-control @error('compare_at_price') is-invalid @enderror"
                                            step="0.01" min="0">

                                    </div>

                                    @error('compare_at_price')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Inventory --}}
                    <div class="card mb-4">

                        <div class="card-header bg-transparent">

                            <h5 class="mb-0">
                                Inventory
                            </h5>

                        </div>


                        <div class="card-body">

                            <div class="row">

                                {{-- Stock --}}
                                <div class="col-md-6 mb-4">

                                    <label for="stock_quantity" class="form-label">

                                        Stock Quantity
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="number" id="stock_quantity" name="stock_quantity"
                                        value="{{ old('stock_quantity', $variant->stock_quantity) }}"
                                        class="form-control @error('stock_quantity') is-invalid @enderror" min="0"
                                        required>

                                    @error('stock_quantity')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- Low Stock --}}
                                <div class="col-md-6 mb-4">

                                    <label for="low_stock_threshold" class="form-label">

                                        Low Stock Threshold
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input type="number" id="low_stock_threshold" name="low_stock_threshold"
                                        value="{{ old('low_stock_threshold', $variant->low_stock_threshold) }}"
                                        class="form-control @error('low_stock_threshold') is-invalid @enderror"
                                        min="0" required>

                                    @error('low_stock_threshold')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Sidebar --}}
                <div class="col-lg-4">

                    <div class="card mb-4">

                        <div class="card-header bg-transparent">

                            <h5 class="mb-0">
                                Variant Settings
                            </h5>

                        </div>


                        <div class="card-body">

                            {{-- Status --}}
                            <div class="form-check form-switch mb-4">

                                <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                    name="is_active" value="1" @checked(old('is_active', $variant->is_active))>

                                <label class="form-check-label" for="is_active">

                                    <strong>
                                        Active Variant
                                    </strong>

                                    <div class="text-muted small">
                                        Customers can purchase this variant.
                                    </div>

                                </label>

                            </div>


                            {{-- Sort Order --}}
                            <div class="mb-3">

                                <label for="sort_order" class="form-label">

                                    Sort Order

                                </label>

                                <input type="number" id="sort_order" name="sort_order"
                                    value="{{ old('sort_order', $variant->sort_order) }}"
                                    class="form-control @error('sort_order') is-invalid @enderror" min="0">

                                @error('sort_order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-muted">
                                    Lower numbers appear first.
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- Product --}}
                    <div class="card mb-4">

                        <div class="card-body">

                            <div class="text-muted small mb-1">
                                Product
                            </div>

                            <h6 class="mb-0">
                                {{ $product->name }}
                            </h6>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="card">

                        <div class="card-body">

                            <button type="submit" class="btn btn-primary w-100 mb-2">

                                <i class="bx bx-save me-1"></i>

                                Update Variant

                            </button>


                            <a href="{{ route('admin.products.variants.index', $product) }}"
                                class="btn btn-outline-secondary w-100">

                                Cancel

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

@endsection
