@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    Orders
                </h4>

                <p class="text-muted mb-0">
                    Manage customer orders and order status.
                </p>
            </div>

        </div>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        @if ($errors->any())

            <div class="alert alert-danger">

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        {{-- Filters --}}

        <div class="card mb-4">

            <div class="card-body">

                <form method="GET" action="{{ route('admin.admin.orders.index') }}">

                    <div class="row g-3">

                        <div class="col-md-5">

                            <label class="form-label">
                                Search
                            </label>

                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Order number, customer or phone">

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Order Status
                            </label>

                            <select name="status" class="form-select">

                                <option value="">
                                    All statuses
                                </option>

                                <option value="pending" @selected(request('status') === 'pending')>
                                    Pending
                                </option>

                                <option value="confirmed" @selected(request('status') === 'confirmed')>
                                    Confirmed
                                </option>

                                <option value="processing" @selected(request('status') === 'processing')>
                                    Processing
                                </option>

                                <option value="shipped" @selected(request('status') === 'shipped')>
                                    Shipped
                                </option>

                                <option value="delivered" @selected(request('status') === 'delivered')>
                                    Delivered
                                </option>

                                <option value="cancelled" @selected(request('status') === 'cancelled')>
                                    Cancelled
                                </option>

                            </select>

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                Payment
                            </label>

                            <select name="payment_status" class="form-select">

                                <option value="">
                                    All
                                </option>

                                <option value="pending" @selected(request('payment_status') === 'pending')>
                                    Pending
                                </option>

                                <option value="paid" @selected(request('payment_status') === 'paid')>
                                    Paid
                                </option>

                                <option value="failed" @selected(request('payment_status') === 'failed')>
                                    Failed
                                </option>

                            </select>

                        </div>


                        <div class="col-md-2 d-flex align-items-end">

                            <button class="btn btn-primary w-100">
                                Filter
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- Orders --}}

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>
                                    Order
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Phone
                                </th>

                                <th>
                                    City
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($orders as $order)
                                <tr>

                                    <td>

                                        <strong>
                                            {{ $order->order_number }}
                                        </strong>

                                    </td>


                                    <td>
                                        {{ $order->customer_name }}
                                    </td>


                                    <td>
                                        {{ $order->customer_phone }}
                                    </td>


                                    <td>
                                        {{ $order->shipping_city }}
                                    </td>


                                    <td>

                                        <strong>
                                            Rs.
                                            {{ number_format($order->total, 2) }}
                                        </strong>

                                    </td>


                                    <td>

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>

                                    </td>


                                    <td>

                                        <span class="badge bg-primary">
                                            {{ ucfirst($order->status) }}
                                        </span>

                                    </td>


                                    <td>
                                        {{ $order->created_at->format('d M Y') }}
                                    </td>


                                    <td>

                                        <a href="{{ route('admin.admin.orders.show', $order) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center py-5">
                                        No orders found.
                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                <div class="mt-3">

                    {{ $orders->links() }}

                </div>

            </div>

        </div>

    </div>

@endsection
