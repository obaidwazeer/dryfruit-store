<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = trim($request->input('search'));

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where(
                                'order_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'customer_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'customer_phone',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->input('status')
                )
            )
            ->when(
                $request->filled('payment_status'),
                fn ($query) => $query->where(
                    'payment_status',
                    $request->input('payment_status')
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }

    public function show(Order $order): View
    {
        $order->load([
            'items',
            'items.product',
            'items.variant',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    public function updateStatus(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,confirmed,processing,shipped,delivered,cancelled',
            ],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return back()->with(
            'success',
            'Order status updated successfully.'
        );
    }

    public function updatePaymentStatus(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'payment_status' => [
                'required',
                'in:pending,paid,failed',
            ],
        ]);

        $order->update([
            'payment_status' => $validated['payment_status'],
        ]);

        return back()->with(
            'success',
            'Payment status updated successfully.'
        );
    }
}
