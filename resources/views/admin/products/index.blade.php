@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')

    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>

                <h6 class="mb-1 text-uppercase">
                    Catalog
                </h6>

                <h4 class="mb-0">
                    Products
                </h4>

            </div>


            @can('products.create')
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">

                    <i class="bx bx-plus me-1"></i>

                    Add Product

                </a>
            @endcan

        </div>


        {{-- Main Card --}}
        <div class="card">

            <div class="card-body">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between mb-4">

                    <div>

                        <h5 class="mb-1">
                            All Products
                        </h5>

                        <small class="text-muted">
                            Manage your dry fruit products and catalog.
                        </small>

                    </div>


                    <span class="badge bg-light text-dark">

                        {{ $products->total() }}

                        {{ Str::plural('Product', $products->total()) }}

                    </span>

                </div>


                {{-- Filters --}}
                <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 mb-4">

                    {{-- Search --}}
                    <div class="col-lg-5 col-md-6">

                        <div class="input-group">

                            <span class="input-group-text bg-transparent">

                                <i class="bx bx-search"></i>

                            </span>

                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search products...">

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-2 col-md-3">

                        <select name="status" class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active" @selected(request('status') === 'active')>

                                Active

                            </option>

                            <option value="inactive" @selected(request('status') === 'inactive')>

                                Inactive

                            </option>

                        </select>

                    </div>


                    {{-- Featured --}}
                    <div class="col-lg-2 col-md-3">

                        <select name="featured" class="form-select">

                            <option value="">
                                All Products
                            </option>

                            <option value="1" @selected(request('featured') === '1')>

                                Featured

                            </option>

                            <option value="0" @selected(request('featured') === '0')>

                                Not Featured

                            </option>

                        </select>

                    </div>


                    {{-- Submit --}}
                    <div class="col-lg-auto">

                        <button type="submit" class="btn btn-primary">

                            <i class="bx bx-filter-alt me-1"></i>

                            Filter

                        </button>

                    </div>


                    {{-- Clear --}}
                    @if (request()->hasAny(['search', 'status', 'featured']))
                        <div class="col-lg-auto">

                            <a href="{{ route('admin.products.index') }}" class="btn btn-light">

                                Clear

                            </a>

                        </div>
                    @endif

                </form>


                {{-- Products Table --}}
                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Product
                                </th>

                                <th>
                                    Categories
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Payment Methods
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Featured
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($products as $product)

                                <tr>

                                    {{-- ID --}}
                                    <td>

                                        <span class="fw-semibold">
                                            {{ $product->id }}
                                        </span>

                                    </td>


                                    {{-- Product --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            {{-- Product Image --}}
                                            <div class="me-3">

                                                @php
                                                    $primaryImage =
                                                        $product->images->firstWhere('is_primary', true) ??
                                                        $product->images->first();
                                                @endphp


                                                @if ($primaryImage)
                                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                                        alt="{{ $product->name }}" width="55" height="55"
                                                        class="rounded object-fit-cover">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                        style="width:55px;height:55px;">

                                                        <i class="bx bx-image text-muted fs-4"></i>

                                                    </div>
                                                @endif

                                            </div>


                                            <div>

                                                <h6 class="mb-1">

                                                    {{ $product->name }}

                                                </h6>


                                                <small class="text-muted">

                                                    {{ $product->slug }}

                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Categories --}}
                                    <td>

                                        @forelse($product->categories as $category)
                                            <span class="badge bg-light text-dark me-1 mb-1">

                                                {{ $category->name }}

                                            </span>

                                        @empty

                                            <span class="text-muted">
                                                No category
                                            </span>
                                        @endforelse

                                    </td>


                                    {{-- Price --}}
                                    <td>

                                        @if (isset($product->price))
                                            <strong>
                                                Rs. {{ number_format($product->price, 2) }}
                                            </strong>
                                        @else
                                            <span class="text-muted">
                                                —
                                            </span>
                                        @endif

                                    </td>

                                    {{-- Payment method --}}
                                    <td>

                                        <div class="d-flex flex-column gap-1">

                                            @if ($product->allow_cod)
                                                <span class="badge bg-success">
                                                    COD
                                                </span>
                                            @endif


                                            @if ($product->allow_online_payment)
                                                <span class="badge bg-primary">
                                                    Online
                                                </span>
                                            @endif


                                            @if (!$product->allow_cod && !$product->allow_online_payment)
                                                <span class="badge bg-danger">
                                                    Disabled
                                                </span>
                                            @endif

                                        </div>

                                    </td>

                                    {{-- Status --}}
                                    <td>

                                        @if ($product->status === 'active')
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>


                                    {{-- Featured --}}
                                    <td>

                                        @if ($product->is_featured)
                                            <span class="badge bg-warning text-dark">

                                                <i class="bx bxs-star me-1"></i>

                                                Featured

                                            </span>
                                        @else
                                            <span class="text-muted">
                                                No
                                            </span>
                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end">

                                        <div class="d-flex justify-content-end gap-2">

                                            @can('products.update')
                                                {{-- Edit --}}
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                    class="btn btn-sm btn-light" title="Edit Product">

                                                    <i class="bx bx-edit"></i>

                                                </a>


                                                {{-- Variants --}}
                                                <a href="{{ route('admin.products.variants.index', $product) }}"
                                                    class="btn btn-sm btn-light" title="Variants">

                                                    <i class="bx bx-package"></i>

                                                </a>
                                            @endcan


                                            @can('products.delete')
                                                {{-- Delete --}}
                                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this product?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-light text-danger"
                                                        title="Delete Product">

                                                        <i class="bx bx-trash"></i>

                                                    </button>

                                                </form>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center py-5">

                                        <div class="mb-3">

                                            <i class="bx bx-package fs-1 text-muted">
                                            </i>

                                        </div>


                                        <h6>
                                            No products found
                                        </h6>


                                        <p class="text-muted mb-3">

                                            Start by creating your first dry fruit product.

                                        </p>


                                        @can('products.create')
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">

                                                <i class="bx bx-plus me-1"></i>

                                                Create Product

                                            </a>
                                        @endcan

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if ($products->hasPages())
                    <div class="mt-4">

                        {{ $products->links() }}

                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
