<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ShippingRateController extends Controller
{
    /**
     * Display all shipping rates.
     */
    public function index(): View
    {
        $shippingRates = ShippingRate::query()
            ->orderBy('city')
            ->paginate(15);

        return view(
            'admin.shipping.index',
            compact('shippingRates')
        );
    }

    /**
     * Show the create form.
     */
    public function create(): View
    {
        return view('admin.shipping.create');
    }

    /**
     * Store a new shipping rate.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'city' => [
                'required',
                'string',
                'max:255',
                'unique:shipping_rates,city',
            ],
            'rate' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        ShippingRate::create([
            'city' => trim($validated['city']),
            'rate' => $validated['rate'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.shipping.index')
            ->with(
                'success',
                'Shipping rate created successfully.'
            );
    }

    /**
     * Show the edit form.
     */
    public function edit(ShippingRate $shippingRate): View
    {
        return view(
            'admin.shipping.edit',
            compact('shippingRate')
        );
    }

    /**
     * Update an existing shipping rate.
     */
    public function update(
        Request $request,
        ShippingRate $shippingRate
    ): RedirectResponse {
        $validated = $request->validate([
            'city' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shipping_rates', 'city')
                    ->ignore($shippingRate->id),
            ],
            'rate' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $shippingRate->update([
            'city' => trim($validated['city']),
            'rate' => $validated['rate'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.shipping.index')
            ->with(
                'success',
                'Shipping rate updated successfully.'
            );
    }

    /**
     * Delete a shipping rate.
     */
    public function destroy(
        ShippingRate $shippingRate
    ): RedirectResponse {
        $shippingRate->delete();

        return redirect()
            ->route('admin.shipping.index')
            ->with(
                'success',
                'Shipping rate deleted successfully.'
            );
    }
}
