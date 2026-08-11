<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function index(Product $product): View
    {
        $variants = $product->variants()
            ->orderBy('sort_order')
            ->orderBy('weight_grams')
            ->paginate(15);

        return view(
            'admin.products.variants.index',
            compact('product', 'variants')
        );
    }

    public function create(Product $product): View
    {
        return view(
            'admin.products.variants.create',
            compact('product')
        );
    }

    public function store(
        StoreProductVariantRequest $request,
        Product $product
    ): RedirectResponse {
        $data = $request->validated();

        $data['product_id'] = $product->id;
        $data['is_active'] = $request->boolean('is_active');

        $product->variants()->create($data);

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Product variant created successfully.');
    }

    public function edit(
        Product $product,
        ProductVariant $variant
    ): View {
        abort_unless(
            $variant->product_id === $product->id,
            404
        );

        return view(
            'admin.products.variants.edit',
            compact('product', 'variant')
        );
    }

    public function update(
        UpdateProductVariantRequest $request,
        Product $product,
        ProductVariant $variant
    ): RedirectResponse {
        abort_unless(
            $variant->product_id === $product->id,
            404
        );

        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active');

        $variant->update($data);

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Product variant updated successfully.');
    }

    public function destroy(
        Product $product,
        ProductVariant $variant
    ): RedirectResponse {
        abort_unless(
            $variant->product_id === $product->id,
            404
        );

        $variant->delete();

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Product variant deleted successfully.');
    }
}
