@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    Order {{ $order->order_number }}
                </h4>

                <p class="text-muted mb-0">
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </p>

            </div>


            <a href="{{ route('admin.admin.orders.index') }}" class="btn btn-outline-secondary">
                Back to Orders
            </a>

        </div>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="row g-4">

            {{-- Customer --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h5 class="mb-4">
                            Customer Information
                        </h5>

                        <p>
                            <strong>Name:</strong><br>
                            {{ $order->customer_name }}
                        </p>

                        <p>
                            <strong>Phone:</strong><br>
                            {{ $order->customer_phone }}
                        </p>

                        <p>
                            <strong>Email:</strong><br>
                            {{ $order->customer_email ?: 'N/A' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Shipping --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h5 class="mb-4">
                            Shipping Information
                        </h5>

                        <p>
                            <strong>City:</strong><br>
                            {{ $order->shipping_city }}
                        </p>

                        <p>
                            <strong>Address:</strong><br>
                            {{ $order->shipping_address }}
                        </p>

                        <p>
                            <strong>Postal Code:</strong><br>
                            {{ $order->shipping_postal_code ?: 'N/A' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Status --}}

            <div class="col-lg-4">

                <div class="card h-100">

                    <div class="card-body">

                        <h5 class="mb-4">
                            Order Status
                        </h5>


                        <form method="POST" action="{{ route('admin.admin.orders.status', $order) }}" class="mb-4">

                            @csrf
                            @method('PATCH')

                            <label class="form-label">
                                Order Status
                            </label>

                            <select name="status" class="form-select mb-2">

                                @foreach (['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach

                            </select>

                            <button class="btn btn-primary w-100">
                                Update Status
                            </button>

                        </form>


                        <form method="POST" action="{{ route('admin.admin.orders.payment-status', $order) }}">

                            @csrf
                            @method('PATCH')

                            <label class="form-label">
                                Payment Status
                            </label>

                            <select name="payment_status" class="form-select mb-2">

                                @foreach (['pending', 'paid', 'failed'] as $status)
                                    <option value="{{ $status }}" @selected($order->payment_status === $status)>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach

                            </select>

                            <button class="btn btn-outline-primary w-100">
                                Update Payment
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>


        {{-- Order Items --}}

        <div class="card mt-4">

            <div class="card-body">

                <h5 class="mb-4">
                    Order Items
                </h5>


                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>
                                    Product
                                </th>

                                <th>
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
                                    Quantity
                                </th>

                                <th>
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($order->items as $item)
                                <tr>

                                    <td>
                                        {{ $item->product_name }}
                                    </td>

                                    <td>
                                        {{ $item->variant_name }}
                                    </td>

                                    <td>
                                        {{ $item->sku }}
                                    </td>

                                    <td>
                                        {{ $item->weight_grams }}g
                                    </td>

                                    <td>
                                        Rs. {{ number_format($item->unit_price, 2) }}
                                    </td>

                                    <td>
                                        {{ $item->quantity }}
                                    </td>

                                    <td>
                                        <strong>
                                            Rs. {{ number_format($item->total, 2) }}
                                        </strong>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- Totals --}}

        <div class="row justify-content-end mt-4">

            <div class="col-lg-4">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Subtotal
                            </span>

                            <strong>
                                Rs. {{ number_format($order->subtotal, 2) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Shipping
                            </span>

                            <strong>
                                Rs. {{ number_format($order->shipping_fee, 2) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Discount
                            </span>

                            <strong>
                                Rs. {{ number_format($order->discount, 2) }}
                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong>
                                Total
                            </strong>

                            <strong class="fs-5">
                                Rs. {{ number_format($order->total, 2) }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        @if ($order->customer_notes)
            <div class="card mt-4">

                <div class="card-body">

                    <h5>
                        Customer Notes
                    </h5>

                    <p class="mb-0">
                        {{ $order->customer_notes }}
                    </p>

                </div>

            </div>
        @endif

    </div>

@endsection
