<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);

        return view('storefront.cart.index', compact('cart'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'variant_id' => [
                'required',
                'integer',
                'exists:product_variants,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
            ],
        ]);

        $variant = ProductVariant::query()
            ->where('id', $validated['variant_id'])
            ->where('is_active', true)
            ->whereHas('product', function ($query) {
                $query->where('status', 'active');
            })
            ->with([
                'product',
                'product.images' => function ($query) {
                    $query
                        ->where('is_primary', true)
                        ->orderBy('sort_order');
                },
            ])
            ->firstOrFail();

        if ($variant->stock_quantity < $validated['quantity']) {
            return back()->withErrors([
                'quantity' => 'The requested quantity is not available.',
            ]);
        }

        $cart = $request->session()->get('cart', []);

        $variantId = (string) $variant->id;

        if (isset($cart[$variantId])) {

            $newQuantity =
                $cart[$variantId]['quantity']
                + $validated['quantity'];

            if ($newQuantity > $variant->stock_quantity) {
                return back()->withErrors([
                    'quantity' => 'You cannot add more than the available stock.',
                ]);
            }

            $cart[$variantId]['quantity'] = $newQuantity;

        } else {

            $primaryImage = $variant->product->images->first();

            $cart[$variantId] = [
                'variant_id' => $variant->id,
                'product_id' => $variant->product_id,
                'name' => $variant->product->name,
                'variant_name' => $variant->name,
                'price' => (float) $variant->price,
                'quantity' => $validated['quantity'],
                'image' => $primaryImage?->path,
                'stock' => $variant->stock_quantity,
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect()
            ->route('storefront.cart.index')
            ->with('success', 'Product added to cart successfully.');
    }

    public function update(
        Request $request,
        string $variantId
    ): RedirectResponse {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
            ],
        ]);

        $cart = $request->session()->get('cart', []);

        if (! isset($cart[$variantId])) {
            return redirect()
                ->route('storefront.cart.index')
                ->withErrors([
                    'cart' => 'Cart item not found.',
                ]);
        }

        $variant = ProductVariant::query()
            ->where('id', $variantId)
            ->where('is_active', true)
            ->whereHas('product', function ($query) {
                $query->where('status', 'active');
            })
            ->first();

        if (! $variant) {
            unset($cart[$variantId]);

            $request->session()->put('cart', $cart);

            return redirect()
                ->route('storefront.cart.index')
                ->withErrors([
                    'cart' => 'This product variant is no longer available.',
                ]);
        }

        if ($validated['quantity'] > $variant->stock_quantity) {
            return back()->withErrors([
                'quantity' => 'The requested quantity exceeds available stock.',
            ]);
        }

        $cart[$variantId]['quantity'] = $validated['quantity'];

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            'Cart updated successfully.'
        );
    }

    public function destroy(
        Request $request,
        string $variantId
    ): RedirectResponse {
        $cart = $request->session()->get('cart', []);

        unset($cart[$variantId]);

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            'Item removed from cart.'
        );
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return redirect()
            ->route('storefront.cart.index')
            ->with(
                'success',
                'Cart cleared successfully.'
            );
    }
}
