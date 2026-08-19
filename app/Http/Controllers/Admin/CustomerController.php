<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = trim($request->input('search'));

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
                }
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.customers.index',
            compact('customers')
        );
    }

    public function show(Customer $customer): View
    {
        $customer->load([
            'orders' => function ($query) {
                $query->latest();
            },
        ]);

        $customer->loadCount('orders');

        $totalSpent = $customer->orders->sum('total');

        return view(
            'admin.customers.show',
            compact(
                'customer',
                'totalSpent'
            )
        );
    }
}
