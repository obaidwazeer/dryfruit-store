@extends('admin.layouts.app')

@section('title', 'Product Variants - ' . $product->name)

@section('content')

    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

            <div>
                <h4 class="mb-1">
                    Product Variants
                </h4>

                <p class="text-muted mb-0">
                    Manage pricing, packaging sizes and inventory for
                    <strong>{{ $product->name }}</strong>.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-secondary">

                    <i class="bx bx-arrow-back me-1"></i>

                    Back to Product

                </a>

                <a href="{{ route('admin.products.variants.create', $product) }}" class="btn btn-primary">

                    <i class="bx bx-plus me-1"></i>

                    Add Variant

                </a>

            </div>

        </div>


        {{-- Product Information --}}
        <div class="card mb-4">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center gap-3">

                            <div>
                                <h5 class="mb-1">
                                    {{ $product->name }}
                                </h5>

                                <small class="text-muted">
                                    {{ $product->slug }}
                                </small>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 text-md-end mt-3 mt-md-0">

                        @if ($product->status === 'active')
                            <span class="badge bg-success">
                                Active
                            </span>
                        @elseif ($product->status === 'draft')
                            <span class="badge bg-warning text-dark">
                                Draft
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                Archived
                            </span>
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Variants --}}
        <div class="card">

            <div class="card-header bg-transparent">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        Variants
                    </h5>

                    {{-- <span class="text-muted">
                        {{ $variants->total() }}
                        {{ Str::plural('variant', $variants->total()) }}
                    </span> --}}
                    <span class="text-muted">
                        {{ $variants->total() }}
                        {{ $variants->total() == 1 ? 'variant' : 'variants' }}
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                @if ($variants->count())

                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead>

                                <tr>

                                    <th class="ps-4">
                                        Variant
                                    </th>

                                    <th>
                                        SKU
                                    </th>

                                    <th>
                                        Weight
                                    </th>

                                    <th>
                                        Price
                                    </th>

                                    <th>
                                        Stock
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end pe-4">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($variants as $variant)
                                    <tr>

                                        {{-- Variant --}}
                                        <td class="ps-4">

                                            <div class="fw-semibold">
                                                {{ $variant->name }}
                                            </div>

                                            <small class="text-muted">
                                                Sort order:
                                                {{ $variant->sort_order }}
                                            </small>

                                        </td>


                                        {{-- SKU --}}
                                        <td>

                                            <span class="badge bg-light text-dark border">
                                                {{ $variant->sku }}
                                            </span>

                                        </td>


                                        {{-- Weight --}}
                                        <td>

                                            <strong>
                                                {{ number_format($variant->weight_grams) }}
                                            </strong>

                                            g

                                        </td>


                                        {{-- Price --}}
                                        <td>

                                            <div class="fw-semibold">

                                                Rs.
                                                {{ number_format((float) $variant->price, 2) }}

                                            </div>


                                            @if ($variant->compare_at_price)
                                                <small class="text-muted text-decoration-line-through">

                                                    Rs.
                                                    {{ number_format((float) $variant->compare_at_price, 2) }}

                                                </small>
                                            @endif

                                        </td>


                                        {{-- Stock --}}
                                        <td>

                                            @if ($variant->stock_quantity <= $variant->low_stock_threshold)
                                                <span class="badge bg-warning text-dark">

                                                    {{ number_format($variant->stock_quantity) }}

                                                    Low Stock

                                                </span>
                                            @else
                                                <span class="badge bg-success">

                                                    {{ number_format($variant->stock_quantity) }}

                                                </span>
                                            @endif

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if ($variant->is_active)
                                                <span class="badge bg-success">
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    Inactive
                                                </span>
                                            @endif

                                        </td>


                                        {{-- Actions --}}
                                        <td class="text-end pe-4">

                                            <div class="d-flex justify-content-end gap-1">

                                                <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Edit Variant">

                                                    <i class="bx bx-edit"></i>

                                                </a>


                                                <form method="POST"
                                                    action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this variant?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Delete Variant">

                                                        <i class="bx bx-trash"></i>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if ($variants->hasPages())
                        <div class="card-footer bg-transparent">

                            {{ $variants->links() }}

                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-5 px-3">

                        <div class="mb-3">

                            <i class="bx bx-package" style="font-size: 55px; opacity: .35;"></i>

                        </div>

                        <h5>
                            No variants found
                        </h5>

                        <p class="text-muted mb-4">
                            Add package sizes such as 250g, 500g or 1kg
                            for this product.
                        </p>

                        <a href="{{ route('admin.products.variants.create', $product) }}" class="btn btn-primary">

                            <i class="bx bx-plus me-1"></i>

                            Add First Variant

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection
